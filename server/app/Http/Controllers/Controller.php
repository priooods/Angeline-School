<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function validating($request, $items) {
        $validate = Validator::make($request->all(), $items);
        if ($validate->fails()) {
            return $this->responses('Failure',401,null,[
                "type" => "error",
                "title" => "Form tidak valid",
                "description" => implode(',', $validate->errors()->all()),
            ]);
        } else {
            return null;
        }
    }

    public function responses($message = '',$code = 200, $data = null, $notification = null) {
        return response()->json([
            'response_status_code' => $code,
            'response_notification' => $notification,
            'response_message'   => $message,
            'response_data'      => $data
        ], $code);
    }

    public function generateCode($length = 5) {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $ret = '';
        for($i = 0; $i < $length; ++$i) {
            $random = str_shuffle($chars);
            $ret .= $random[0];
        }
        return $ret;
    }
}
