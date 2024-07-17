<?php

namespace App\Traits;

trait APIResponseTrait
{
    /* --- success method --- */
    protected function success($data, int $code = 200, string $msg = null)
    {

        return response()->json([
            'status' => 'success',
            'message' => $msg,
            'data' => $data
        ], $code);
    }

    /* --- failed method --- */
    protected function failed(array $data, int $code = 500, string $msg = null)
    {

        return response()->json([
            'status' => 'failed',
            'message' => $msg,
            'data' => $data
        ], $code);
    }
}
