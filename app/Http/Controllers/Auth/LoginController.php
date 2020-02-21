<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Libraries\AuthLibrary;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function index()
    {
        $query=array();
        $body_css='login-bg';
        $sKey= session()->get('skey');
        $flag='testnav';
        return view('auth.login',compact('query','body_css','sKey','flag'));
    }
    public function signin(Request $request)
    {

        $Skey=session()->get('skey');
        $mobile_key='mobile'.$Skey;
        $email_key='email'.$Skey;
        $country_code_key='country_code'.$Skey;
        $mobile=$request->input($mobile_key);
        $email=$request->input($email_key);
        $country_code=str_replace('+','',$request->input($country_code_key));
        $full_mobile=$country_code.$mobile;
        $res=AuthLibrary::Login(array('mobile'=>$full_mobile,'email'=>$email,'country_code'=>$country_code));

        if($res->status=='success')
        {
            AuthLibrary::PinSession($res);
        }
        echo json_encode($res);


    }
    public function pin(Request $request)
    {
        $Skey=session()->get('skey');
        $mobile_key='LoginMobile'.$Skey;
        $country_code_key='LoginCountryCode'.$Skey;
        $pin_key='pin'.$Skey;
        $request_id_key='LoginRequestId'.$Skey;
        $mobile=session()->get($mobile_key);
        $country_code=session()->get($country_code_key);
        $request_id=session()->get($request_id_key);
        $pin=$request->input($pin_key);
        $res=AuthLibrary::PinConfirmation(array('mobile'=>$mobile,'country_code'=>$country_code,'request_id'=>$request_id,'pin'=>$pin));
        if($res->message=='success')
        {
            AuthLibrary::LoginSession($res);
        }
        echo json_encode($res);
    }
    public function resend_pin()
    {
        $res=AuthLibrary::ResendPin();
        echo json_encode($res);
    }
    public function logout()
    {
        AuthLibrary::LogOut();
        return redirect(route('auth.login'));
    }
}
