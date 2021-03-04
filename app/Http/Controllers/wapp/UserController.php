<?php


namespace App\Http\Controllers\wapp;


use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function getSessionKey(Request $request){
        $code=$request->input('code',false);
        info('code',$request->all());
        $client=new Client();
        $url='https://api.weixin.qq.com/sns/jscode2session?appid=APPID&secret=SECRET&js_code=JSCODE&grant_type=authorization_code';

        $response=$client->request('get','https://api.weixin.qq.com/sns/jscode2session',[
            'query'=>[
                'appid'=>'wx7953275966ce1f8b',
                'secret'=>'1873a2ee04b78f1aa602ab5ae2ea361f',
                'js_code'=>$code,
                'grant_type'=>'authorization_code'
            ]
        ]);
        $arr=json_decode($response->getBody(),true);
        info('getInfo',$arr);

        try{
            $session_key=$arr['session_key'];
            $open_id=$arr['openid'];
        }catch (\Throwable $e){
            return $this->_response(500,$e->getMessage(),$e->getTrace());
        }
        /**
         * 服务器A拿到session_key后，生成一个随机数3rd_session,以3rdSessionId为key,以session_key + openid为value缓存到redis或memcached中；
         */
        return $this->_response(0,'成功',$arr);


    }

    //未绑定登陆
    public function userLogin(Request $request){
        $open_id=$request->input('openid','');

        $avatarUrl=$request->input('avatarUrl','');
        $nickName=$request->input('nickName','');
        $gender=$request->input('gender',3);

        $language=$request->input('language','zh_CN');
        $country=$request->input('country','');
        $province=$request->input('province','');
        $city=$request->input('city','');

        try{
            $exist=DB::table('users')->where('open_id',$open_id)->first();
            if($exist){
//                DB::table('user')->where('open_id',$open_id)
//                    ->update([
//                        'avatar'=>$avatarUrl,
//                        'nickname'=>$nickName,
//                        'gender'=>$gender
//                    ]);
                return $this->_response(0,'游客登陆get',$exist);
            }
            else{
                $random_acc=$this->random_account();
                $id=DB::table('users')->insertGetId([
                    'account'=>$random_acc,
                    'password'=>Hash::make('123456'),
                    'nickname'=>$nickName,
                    'gender'=>$gender,
                    'developer'=>0,
                    'avatar'=>$avatarUrl,
                    'email'=>$random_acc.'@temp.com',
                    'open_id'=>$open_id,
                    'created_at'=>date('Y-m-d H:i:s')
                ]);
                $user=DB::table('users')->find($id);
                return $this->_response(0,'游客登陆ins',$user);
            }
        }catch (\Throwable $e){
            return $this->_response(500,$e->getMessage(),$e->getTrace());
        }


    }

    public function get_test(Request $request){
        return response()->json([
            'test'=>1,
            'msg'=>2,
            'data'=>3
        ]);
    }
}