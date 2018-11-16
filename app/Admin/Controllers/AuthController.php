<?php
namespace App\Admin\Controllers;
use Encore\Admin\Controllers\AuthController as BaseAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Encore\Admin\Facades\Admin;
use Illuminate\Support\Facades\DB;
class AuthController extends BaseAuthController
{
    public function getLogin()
    {
        if ($this->guard()->check()) {
            return redirect($this->redirectPath());
        }

        return view('admin.login');
    }

    public function postLogin(Request $request)
    {
        $credentials = $request->only([$this->username(), 'password','captcha']);

        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($credentials, [
            $this->username()   => 'required',
            'password'          => 'required',
            'captcha' => 'required|captcha'
        ], [
            'required' => ':attribute不能为空',
        ], [
            'username' => '账号',
            'password' => '密码',
            'captcha' => '验证码'
        ]);


        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        unset($credentials['captcha']);

        if ($this->guard()->attempt($credentials)) {
            DB::table('admin_users')
                ->where('id', Admin::user()->id)
                ->update(['last_login_time'=>date('Y-m-d H:i:s'), 'last_login_ip'=>$_SERVER['REMOTE_ADDR']]);
            return $this->sendLoginResponse($request);
        }

        return back()->withInput()->withErrors([
            $this->username() => $this->getFailedLoginMessage(),
        ]);
    }
    protected function getFailedLoginMessage()
    {
        return Lang::has('auth.failed')
            ? trans('auth.failed')
            : '验证不通过';
    }

}