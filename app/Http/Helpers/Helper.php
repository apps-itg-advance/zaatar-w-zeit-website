<?php

namespace App\Http\Helpers;

use App\Http\Controllers\Controller;
use GuzzleHttp\Exception as GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class Helper extends Controller
{
    private static $_instance = null;
    private static $array = [];
    private static $object;
    private static $excluded = [];

    function __construct()
    {
    }

    public static function isJSON($string)
    {
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }

    public static function prettyMsg($key)
    {
        $string = str_replace("_", " ", $key);
        $string = str_replace("id", "", $string);
        return strtoupper($string);
    }

    public static function prettyJsonEncode(array $array)
    {
        $result = '';
        foreach ($array as $key => $value)
            if (!empty($value))
                $result .= strtoupper($key) . ': ' . $value . '<br>';
        return $result;
    }

    public static function getApi($url, $token = null)
    {
        try {
            $client = new Client();
            $headers['Accept'] = 'application/json';
            if (!empty($token)) $headers['Authorization'] = 'Bearer ' . $token;
            $options = ['headers' => $headers];
            $request = $client->get($url, $options);
            $response = $request->getBody();
            return json_decode((string)$response);
        } catch (GuzzleException\BadResponseException $e) {
            $body = $e->getResponse()->getBody()->getContents();
            $array = json_decode($body);
            if (isset($array->error) and $array->error == 'Token not valid') {
                session()->flush();
                cache()->clear();
                return redirect('home.menu');
            }
            return (object)array();
        }
    }

    public static function postApi($url, $body, $withLoyalty = true)
    {
        try {
            $params = [];
            $skey = session()->get('skey');
            $s_org = session()->get('_org');
            if (!isset($body['token'])) {
                $token = $s_org->token;
                if (session()->has('token')) {
                    $token = session()->get('token');
                }
                $params['token'] = $token;
            }
            if (!isset($body['organization_id'])) {
                $params['organization_id'] = $s_org->id;
            }
            if (!isset($body['channel_id'])) {
                $params['channel_id'] = 1;
            }
            if ($withLoyalty) {
                $user = session()->has('user' . $skey) ? session()->get('user' . $skey) : array();
                $params['LoyaltyId'] = $user->details->LoyaltyId;
            }
            $body = array_merge($body, $params);
            $client = new Client();
            $options = [
                'form_params' => $body
            ];
            $response = $client->post($url, $options);
            $response = json_decode((string)$response->getBody());
            return $response;
        } catch (GuzzleException\BadResponseException $e) {
            $content = json_decode((string)$e->getResponse()->getBody()->getContents());
            return $content;
        }
    }

    public static function camelToSnakeCase($array)
    {
        $aa = array();
        foreach ($array as $k => $v) {
            $kk = Str::snake($k);

            if (is_array($v) || is_object($v)) {
                $v = self::camelToSnakeCase($v);
            }
            if (is_object($array)) {
                $aa[$kk] = $v;
            } else {
                $array[$kk] = $v;
                unset($array[$k]);
            }
        }
        return $array;
    }
}
