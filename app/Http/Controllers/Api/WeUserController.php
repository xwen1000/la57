<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class WeUserController extends Controller
{
	
	public function me()
	{
		$token = request()->input('token', '');
        
        $tokenInfo = DB::table('member_tokens')
        				->where('token', $token)
        				->first();
        if(!$tokenInfo) {
            return response()->json(['result'=>'fail', 'error_info'=>'校验失败']);
        }
        $time = time();
        if($tokenInfo->expires < time()) {
            return response()->json(['result'=>'fail', 'error_info'=>'校验失败']);
        }
        $memberInfo = DB::table('members')->where('id', $tokenInfo->member_id)->first();

        if(!$memberInfo) {
            return response()->json(['result'=>'fail', 'error_info'=>'校验失败']);
        }
        return response()->json(['result'=>'ok', 'user_info'=>$memberInfo]);
	}
	
}