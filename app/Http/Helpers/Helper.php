<?php

namespace App\Http\Helpers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use GuzzleHttp\Exception as GuzzleException;
use GuzzleHttp\Client;
use Mockery\Exception;
use Illuminate\Support\Str;

class Helper extends Controller
{
	private static $_instance = null;

	private static $array = [];
	private static $object;
	private static $excluded = [];

	function __construct(){}

	public static function isJSON($string){
		return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
	}

	public static function prettyMsg($key){
		//display pretty messages - remove "_" and "id"
		$string = str_replace("_"," ", $key);
		$string = str_replace("id", "", $string);
		return strtoupper($string);
	}

	public static function prettyJsonEncode(Array $array){
		$result = '';
		foreach($array as $key => $value)
			if(!empty($value))
				$result .= strtoupper($key) . ': ' . $value . '<br>';
		return $result;
	}

	public static function getApi($url,$token = null)
	{

		try {
			$client = new Client();
			$headers['Accept'] = 'application/json';
			if(!empty($token)) $headers['Authorization'] = 'Bearer ' . $token;
			$options = ['headers' => $headers];
			$request = $client->get($url,$options);
			//dump($request);
//die;
			$response = $request->getBody();
			return json_decode((string)$response);
		}catch (GuzzleException\BadResponseException $e) {
           // session()->flush();
            // cache()->clear();
//		    echo $e;
		    //dump($request);
			//$content = json_decode((string)$e->getResponse()->getBody()->getContents());
			//dd($content);
			//throw new Exception($e->getResponse()->getBody()->getContents(),$e->getResponse()->getStatusCode());
            return (object)array();
		}
	}

	public static function postApi($url,$body) {
		try {
			$client = new Client();
            $options = [
                'form_params' => $body
            ];

			$response = $client->post($url,$options);
			$response = json_decode((string)$response->getBody());

			return $response;

		}catch (GuzzleException\BadResponseException $e) {
			$content = json_decode((string)$e->getResponse()->getBody()->getContents());
			return $content;
			//dd($e->getMessage());
			//throw new Exception($e->getMessage(),$e->getResponse()->getStatusCode());
		}
	}


	public static function camelToSnakeCase($array){

		$aa = array();
		foreach($array as $k => $v){
			$kk = Str::snake($k);

			if(is_array($v) || is_object($v)){
				$v = self::camelToSnakeCase($v);
			}

			if(is_object($array)) {
				$aa[$kk] = $v;
//				unset($array->$k);
			}else{
				$array[$kk] = $v;
				unset($array[$k]);
			}
		}
		return $array;
	}
}
