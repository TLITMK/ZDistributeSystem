<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Validation\ValidationException;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function ValidateMsg(ValidationException $e){
        foreach ($e->errors() as $key=>$v){
            return $v[0];
        }
    }

    function _response($code,$msg,$data){
        return response()->json([
            'err_code'=>$code,
            'msg'=>$msg,
            'data'=>$data
        ]);
    }

    function random_account(){
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyz 
               ABCDEFGHIJKLOMNOPQRSTUVWXYZ';
        $key='_temp_';
        for($i=0;$i<12;$i++)
        {
            $key .= $pattern{mt_rand(0,35)};    //生成php随机数
        }
        return $key;

    }


}
