<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

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
}
