<?php


namespace App\Http\Controllers\admin;


use App\Http\Controllers\Controller;
use http\Env\Response;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function permission_all(Request $request){
        $list=\DB::table('permissions')->get();
        return response()->json($list);
    }

    //add + edit
    public function permission_edit(Request $request){
        $id=$request->input('id',false);
        if($id){
            //修改
            try{
                $rt=\DB::table('permissions')->where('id',$id)
                    ->update([
                        'name'=>$request->input('name',''),
                        'guard_name'=>$request->input('guard_name',''),
                        'path'=>$request->input('path',''),
                        'title'=>$request->input('title',''),
                        'component'=>$request->input('component',''),
                        'breadcrumb'=>$request->input('breadcrumb',1),
                        'hidden'=>$request->input('hidden',0),
                        'noCache'=>$request->input('noCache',0),
                        'redirect'=>$request->input('redirect',''),
                        'activeMenu'=>$request->input('activeMenu',''),
                        'parent_id'=>$request->input('parent_id',''),
                        'level'=>$request->input('parent_id',''),
                        'sort'=>$request->input('sort',0),
                        'updated_at'=>date('Y-m-d H:i:s')
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
            if(
                !$request->input('name',false)||
                !$request->input('guard_name',false)||
                !$request->input('path',false)||
                !$request->input('title',false)||
                !$request->input('level',false)||
                !$request->input('component',false)

            )
            {
                return response()->json([
                    'err_code'=>200,
                    'msg'=>'参数错误',
                    'data'=>''
                ]);
            }
            try{
                \DB::table('permissions')->insert([
                    'name'=>$request->input('name',''),
                    'guard_name'=>$request->input('guard_name',''),
                    'path'=>$request->input('path',''),
                    'title'=>$request->input('title',''),
                    'component'=>$request->input('component',''),
                    'breadcrumb'=>$request->input('breadcrumb',1),
                    'hidden'=>$request->input('hidden',0),
                    'noCache'=>$request->input('noCache',0),
                    'redirect'=>$request->input('redirect',''),
                    'activeMenu'=>$request->input('activeMenu',''),
                    'parent_id'=>$request->input('parent_id',0),
                    'sort'=>$request->input('sort',0),
                    'level'=>$request->input('level',0),
                    'updated_at'=>date('Y-m-d H:i:s')]);
            }catch (\Throwable $e){
                return response()->json([
                    'err_code'=>500,
                    'msg'=>$e->getMessage(),
                    'data'=>$e->getTrace()
                ]);
            }

            //插入
            return response()->json([
                'err_code'=>0,
                'msg'=>'插入成功',
                'data'=>''
            ]);
        }

    }

    //del
    public function permission_del(Request $request){
        $id=$request->input('id');
        if(!$id){
            return response()->json([
                'err_code'=>200,
                'msg'=>'参数错误',
                'data'=>''
            ]);
        }
        try{
            \DB::table('permissions')
                ->where('id',$id)
                ->orWhere('parent_id',$id)
                ->delete();
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
}