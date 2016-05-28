<?php

// namespace App\Http\Controllers\Service;

// use App\Http\Controllers\Controller;
// // use App\Tool\Validate\ValidateCode;
// use App\Tool\SMS\sendTemplateSMS;

// use Illuminate\Http\Request;
// use App\Http\Requests;
// use App\Models\M3Result;



// use App\Entity\TempPhone;
// use App\Entity\Member;
// use App\Models\M3Email;
// use App\Tool\UUID;
// use Mail;
namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\M3Result;
use App\Entity\Member;
use App\Entity\TempPhone;
use App\Entity\TempEmail;
use App\Models\M3Email;
use App\Tool\UUID;
use Mail;
class MemberController extends Controller
{
    public function register(Request $request){
        $email=$request->input("email");
        $phone=$request->input("phone");
        $password=$request->input("password");
        $phone_code=$request->input("phone_code");
        $validate_code=$request->input("validate_code");

        $m3_result=new M3Result;

        //后端校验
        if ($phone==''&& $email=='') {
        	$m3_result->status=5;
        	$m3_result->message="手机或密码不能为空";
        	return $m3_result->toJson();
        }
        //手机注册
        if ($phone!='') {
        	//手机验证码 有效期 orm
        	$tempPhone=TempPhone::where('phone',$phone)->first();

        	 // $oldtime=strtotime($tempPhone->deadline);
        	 // return $m3_result->toJson();
            
        	if ($tempPhone->code==$phone_code) {
        	    if (time()>strtotime($tempPhone->deadline)) {
        	    	$m3_result->status=7;
        	    	$m3_result->message="手机验证码不正确";
        	    	return $m3_result->toJson();
        	    }
        	    $member=new Member;
        	    $member->phone=$phone;
        	    $member->password=md5($password);
        	    $member->save();

        	    $m3_result->status=0;
        	    $m3_result->message="注册成功";
        	    return $m3_result->toJson();
        	}
        }
        //邮箱注册
        if($email!='') {
            //验证码有没有 //验证码 session中取 
            $validate_codefrom=$request->session()->get("validate_code",'');           
               
               if ($validate_code!=$validate_codefrom) {
                   $m3_result->status=8;
                   $m3_result->message="验证码不正确";
                   return $m3_result->toJson();
                }
            //邮箱注册信息保存到数据库
            $member=new Member;
            $member->email=$email;
            $member->password=md5($password);
            $member->save();

            $uuid=UUID::create(); 
           
            $m3_email=new M3Email;
            $m3_email->to=$email;
            $m3_email->cc='706583527@qq.com';
            $m3_email->subject="欢迎注册我们的网站，请激活您的账号！";
            $m3_email->content="请于24小时完成验证激活，http://www.la.com/server/validate_email"
            ."?member_id=".$member->id
            ."&code=".$uuid;
            
            $tempEmail = new TempEmail;
            $tempEmail->member_id = $member->id;
            $tempEmail->code = $uuid;
            $tempEmail->deadline = date('Y-m-d H-i-s', time() + 24*60*60);
            $tempEmail->save();

            //发送激活邮件，通过163邮箱 ,
            
            Mail::send('activemail', ['m3_email'=>$m3_email], function($message) use($m3_email)
            {
                $message->to($m3_email->to,'nickname')->cc($m3_email->cc)->subject($m3_email->subject);
            });

            $m3_result->status=0;
            $m3_result->message="注册成功";
            return $m3_result->toJson();
            
        }
    }
    public function login(Request $request){
        //获取参数
        $username=$request->input('username','');
        $password=md5($request->input('password',''));
        $validate_code=$request->input('validate_code','');
        //校验

        //判断 验证码
        $m3_result=new M3Result;
        // $m3_result->status=10;
        // $m3_result->message=$username.$password.$validate_code;
        // return $m3_result->toJson();
        $validate_code_session=$request->session()->get('validate_code','');
        if ($validate_code_session!=$validate_code) {
            $m3_result->status=1;
            $m3_result->message="验证码有误";
            return $m3_result->toJson();
        }
        //邮箱登录
        if (strstr($username, "@")==false) {
            $member=Member::where('phone',$username)->first();
        }else{
            $member=Member::where('email',$username)->first();
        }
        if ($member==null) {
            //用户未注册
            $m3_result->status=2;
            $m3_result->message="用户未注册";
            return $m3_result->toJson();
        }else{
            if ($password!=$member->password) {
                //用户未注册
            $m3_result->status=2;
            $m3_result->message="密码不正确";
            return $m3_result->toJson();
            }

        }
        $m3_result->status=0;
        $m3_result->message="登录成功";
        //保存到session中
        $request->session()->put('userinfo',$member);
        return $m3_result->toJson();


    
    }
}
