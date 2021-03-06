<?php


namespace App\Http\Controllers\admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function getList(Request $request){
        $count=$request->input('pageSize',10);

        $list=Role::where(function($query)use($request){
                $searchType=$request->input('searchType',false);
                $searchValue=$request->input('searchValue',false);
                if($searchType&&$searchValue)
                    $query->where($searchType,'like','%'.$searchValue.'%');
            })
            ->with('permissions')
            ->paginate($count);
        return response()->json($list);
    }

    public function edit(Request $request){
        try{
            $request->validate([
                'name'=>'string|required|min:2|max:10',
                'title'=>'string|required|min:2|max:20',
                'guard_name'=>'string|required|min:2|max:10'
            ]);
        }catch (ValidationException $e){
            return response()->json([
                'err_code'=>200,
                'msg'=>$this->ValidateMsg($e),
            ]);
        }
        $id=$request->input('id',false);
        if($id){
            //编辑

            try{
                \DB::table('roles')->where('id',$id)
                    ->update([
                        'name'=>$request->input('name'),
                        'title'=>$request->input('title'),
                        'guard_name'=>$request->input('guard_name')
                    ]);
            }catch (\Throwable $e){
                return response()->json([
                    'err_code'=>500,
                    'msg'=>$e->getMessage(),
                    'data'=>$e->getTrace()
                ]);
            }
            return response()->json([
                'err_code'=>0,
                'msg'=>'修改成功',
                'data'=>''
            ]);
        }
        else{
            try{
                \DB::table('roles')->insert([
                    'name'=>$request->input('name'),
                    'title'=>$request->input('title'),
                    'guard_name'=>$request->input('guard_name')
                ]);
            }catch (\Throwable $e){
                return response()->json([
                    'err_code'=>500,
                    'msg'=>$e->getMessage(),
                    'data'=>$e->getTrace()
                ]);
            }
            return response()->json([
                'err_code'=>0,
                'msg'=>'添加成功',
                'data'=>''
            ]);
            //插入
        }
    }

    public function del(Request $request){
        try{

            $request->validate([
                'id'=>'numeric|required'
            ]);
        }catch (ValidationException $e){
            return response()->json([
                'err_code'=>200,
                'msg'=>$this->ValidateMsg($e),
            ]);
        }
        try{
            $bool=\DB::table('roles')->where('id',$request->input('id'))->delete();
        }catch (\Throwable $e){
            return response()->json([
                'err_code'=>500,
                'msg'=>$e->getMessage(),
                'data'=>$e->getTrace()
            ]);
        }
        return response()->json([
            'err_code'=>0,
            'msg'=>'删除成功',
            'data'=>''
        ]);
    }
    public function setPermission(Request $request){
        try{
            $request->validate([
                'permission_ids'=>'required|array',
                'role_id'=>'required|numeric'
            ]);
        }catch (ValidationException $e){
            return response()->json([
                'err_code'=>200,
                'msg'=>$this->ValidateMsg($e),
            ]);
        }
        try{
            $ids=$request->input('permission_ids');
            $role_id=$request->input('role_id');
            $role=Role::where('id',$role_id)->first();
            $permissions=Permission::whereIn('id',$ids)->get();
            $rt=$role->syncPermissions($permissions);
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