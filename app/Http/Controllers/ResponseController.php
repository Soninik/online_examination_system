<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResponseController extends Controller
{
    public function send_success($data, $msg)
    {
        $response = [
            'success' => true,
            'data' => $data,
            'msg' => $msg
        ];
        return response()->json($response, 200);
    }

    public function send_error($error, $errorMsg = [])
    {

        $response = [
            'success' => false,
            'msg' => $error,
        ];
        if (!empty($errorMsg)) {
            $response['data'] = $errorMsg;
        }
        return response()->json($response, 200);
    }

    public function fail_api($error, $errorMsg = [])
    {

        $response = [
            'success' => false,
            'msg' => $error,
        ];
        if (!empty($errorMsg)) {
            $response['data'] = $errorMsg;
        }
        return response()->json($response, 404);
    }
}
