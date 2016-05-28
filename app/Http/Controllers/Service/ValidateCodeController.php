<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Tool\Validate\ValidateCode;
use App\Tool\SMS\sendTemplateSMS;
use App\Entity\TempPhone;
use App\Entity\TempEmail;
use App\Entity\Member;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\M3Result;
class ValidateCodeController extends Controller
{
   public function create(Request $request){
   	  $validateCode=new ValidateCode();
      //验证码存入session
      $request->session()->put('validate_code',$validateCode->getCode());

     	return $validateCode->doimg();
   }

   public function sendSMS(Request $request){
      $M3Result=new M3Result();
      //$phone=$_GET['phone'];
      $phone=$request->input('phone');
   	  //$phone=Input::get('phone');
   	  if ($phone=="") {       
   	  	$M3Result->status=1;
   	  	$M3Result->message="手机号不能为空";
   	  	return $M3Result->toJson();
   	  }
      $sendTemplateSMS=new sendTemplateSMS();
      //生成验证码
        $charset = '1234567890';//随机因子
        $code="";//验证码
        $codelen = 6;//验证码长度
        $_len = strlen($charset) - 1;
          for ($i = 0;$i < $codelen;++$i) {
               $code.= $charset[mt_rand(0, $_len)];
        }
       $M3Result=$sendTemplateSMS->sendTemplateSMS($phone,array($code,60),1);//60min

       if ($M3Result->status==0) {
       	//保存 到数据库中
        $tempPhone=TempPhone::where('phone',$phone)->first();
        if ($tempPhone==null) {
          $tempPhone=new TempPhone;
        }
       
       $tempPhone->phone=$phone;
       $tempPhone->deadline=date('Y-m-d H-i-s',time()+60*60);
       $tempPhone->code=$code;
       $tempPhone->save();

       }
      return $M3Result->toJson();      
   }

  public function validateEmail(Request $request){
    $member_id = $request->input('member_id', '');
    $code = $request->input('code', '');
    if($member_id == '' || $code == '') {
      return '验证异常';
    }

    $tempEmail = TempEmail::where('member_id', $member_id)->first();
    if($tempEmail == null) {
      return '验证异常';
    }

    if($tempEmail->code == $code) {
      if(time() > strtotime($tempEmail->deadline)) {
        return '该链接已失效';
      }

      $member = Member::find($member_id);
      $member->active = 1;
      $member->save();

      return redirect('/login');
    } else {
      return '该链接已失效';
    }
  }
}
