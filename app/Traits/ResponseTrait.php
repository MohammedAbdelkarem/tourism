<?php

namespace App\Traits;

trait ResponseTrait
{
    public function SendResponse($status = null , $message = null , $data = null)
    {
        $data = [
            'data' => $data,
            'message' => $message,
            'status' => $status,
        ];
        // return response($array);

        return response()->json($data , $status);
    }
}

