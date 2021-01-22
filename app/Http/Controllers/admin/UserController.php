<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * 登录
     */
    public function login(LoginRequest $request)
    {
        $data = $request->only(['account', 'password']);

        $remember = $request->remember ?: false;

        if (Auth::attempt($data, $remember)) {
            $user = auth()->user();
            $expiredAt = null;
            if (!$remember) {
                $expiredAt = Carbon::now()->addMinute(config('session.lifetime'))->toDateTimeString();
            }
            $user->expiredAt = $expiredAt;
            return response()->json($user, 200);
        } else {
            return response()->json(['message' => '账号或密码错误'], 401);
        }
    }

    /**
     * 登出
     */
    public function logout()
    {
        Auth::guard('web')->logout();

        return response()->json(['message' => '登出成功'], 200);
    }

    /**
     * 获取当前用户信息
     */
    public function userInfo(Request $request)
    {
        $user = $request->user();
        if($user->developer){
            $permission=Permission::with('roles')->get();
        }
        else{
            $permission=$user->getAllPermissions();
        }
        $user->menus=$this->getMenu($permission);

        return response()->json($user, 200);
    }



    function getMenu($permissions,$parent_id=0){
        $array=[];
        foreach ($permissions as $key=>$permission){
            if($permission['parent_id']===$parent_id){
                $permission['children']=$this->getMenu($permissions,$permission['id']);
                $array[]=$permission;
            }
        }
        return $array;
    }

    public function test(){
        $user=User::first();
        $user->menus=$this->getMenu($user->getAllPermissions());
        return response()->json($user,200);
    }

    public function admin_list(Request $request){
        $count=$request->input('pageSize',10);
        $list=User::where(function($query)use($request){
            $searchType=$request->input('searchType','account');
            $searchValue=$request->input('searchValue',false);
            if($searchValue&&$searchType)
                $query->where($searchType,'like','%'.$searchValue.'%');

        })->with('roles')
            ->paginate($count);
        foreach ($list as &$v){
            $v->role_str='';
            foreach ($v->roles as $role){
                $v->role_str.=$role->title.'|';
            }
        }
        return response()->json($list->toArray());
    }

    public function admin_edit(Request $request){

        $id=$request->input('id',false);
        if($id){
            //修改
            try{
                $request->validate([
                    'account'=>'required|max:18|min:6',
                    'nickname'=>'required|max:12',
                    'email'=>'required|email'
                ]);
            }catch (ValidationException $e){
                return response()->json([
                    'err_code'=>200,
                    'msg'=>$this->ValidateMsg($e)
                ]);
            }
            try{
                $rt=\DB::table('users')->where('id',$id)->update([
                    'account'=>$request->input('account'),
                    'nickname'=>$request->input('nickname','用户#'.random_bytes(5)),
                    'gender'=>$request->input('gender',3),
                    'developer'=>$request->input('developer',0),
                    'email'=>$request->input('email'),
                    'signature'=>$request->input('signature',''),
                    'updated_at'=>date('Y-m-d H:i:s')

                ]);
            }catch(\Throwable $e){
                return response()->json([
                    'err_code'=>500,
                    'msg'=>$e->getMessage(),
                    'data'=>$e->getTrace()
                ]);
            }
            return response()->json([
                'err_code'=>0,
                'msg'=>'修改成功'
            ]);

        }
        else{
            //插入
            try{

                $request->validate([
                    'account'=>'required|max:18|min:6',
                    'nickname'=>'required|max:12',
                    'email'=>'required|email',
                    'password'=>'required|max:18|min:6'
                ]);
            }catch (ValidationException $e){
                return response()->json([
                    'err_code'=>200,
                    'msg'=>$this->ValidateMsg($e)
                ]);
            }
            try{
                $faker = \Faker\Factory::create('zh_CN');
                $rt=\DB::table('users')->where('id',$id)->insert([
                    'account'=>$request->input('account'),
                    'nickname'=>$request->input('nickname','用户#'.random_bytes(5)),
                    'gender'=>$request->input('gender',3),
                    'developer'=>$request->input('developer',0),
                    'avatar'=>$request->input('avatar', $faker->imageUrl(256,256)),
                    'email'=>$request->input('email'),
                    'signature'=>$request->input('signature',''),
                    'created_at'=>date('Y-m-d H:i:s'),
                    'password'=>Hash::make($request->input('password')),
                    'email_verified_at'=>null

                ]);
            }catch(\Throwable $e){
                return response()->json([
                    'err_code'=>500,
                    'msg'=>$e->getMessage(),
                    'data'=>$e->getTrace()
                ]);
            }
            return response()->json([
                'err_code'=>0,
                'msg'=>'添加成功'
            ]);
        }
    }

    public function admin_del(Request $request){
        $id=$request->input('id');
        if(!$id){
            return response()->json([
                'err_code'=>200,
                'msg'=>'参数错误',
                'data'=>''
            ]);
        }
        try{
            \DB::table('users')->where('id',$id)->delete();
        }catch (\Throwable $e){
            return response()->json([
                'err_code'=>500,
                'msg'=>$e->getMessage(),
                'data'=>$e->getTrace()
            ]);
        }
        return response()->json([
            'err_code'=>0,
            'msg'=>'删除成功'
        ]);
    }

    public function admin_upload_avatar(Request $request){
        $files=$request->allFiles();
        $user_id=$request->input('uid');
        $base64=$request->input('file');
//        $request->validate([
//            'file'=>'image|required|mimes:jpg,png',
//        ]);
        $bool=Storage::disk('avatar')->put($user_id.'.png',base64_decode(explode(',',$base64)[1]));
        if(!$bool){
            return response()->json([
                'err_code'=>500,
                'msg'=>'上传失败'
            ]);
        }
        $path=$request->server('REQUEST_SCHEME').'://'.$request->server('HTTP_HOST').Storage::url('avatar/'.$user_id.'.png');
        \DB::table('users')->where('id',$user_id)
            ->update([
                'avatar'=>$path
            ]);
        return response()->json([
            'err_code'=>0,
            'msg'=>'上传成功',
            'data'=>$path
        ]);
    }

    public function setRoles(Request $request){
        try{
            $request->validate([
                'user_id'=>'required|numeric',
                'role_ids'=>'required|array'
            ]);
        }catch (ValidationException $e){
            return response()->json([
                'err_code'=>200,
                'msg'=>$this->ValidateMsg($e),
            ]);
        }

        try{
            $ids=$request->input('role_ids');
            $user_id=$request->input('user_id');
            $user=User::where('id',$user_id)->first();
            $roles=Role::whereIn('id',$ids)->get();
            $rt=$user->assignRole($roles);
        }catch (\Throwable $e){
            return response()->json([
                'err_code'=>500,
                'msg'=>$e->getMessage(),
                'data'=>$e->getTrace()
            ]);
        }
        return response()->json([
            'err_code'=>0,
            'msg'=>'设置成功',
            'role'=>$rt
        ]);
    }
}
