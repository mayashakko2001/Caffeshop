<?php
namespace App\Traits;
trait GenTraits
{
    
    
    protected function success($data, int $code = 200, string $message = null)
    {
        return response()->json([
            'status' =>true,
            'data' => $data,
            'message' => $message ?? "The operation was completed successfully",
            'code' => $code
        ]);
    }
    protected function error($errors = null, string  $message = null, int $code = 500)
    {
        return response()->json([
            'errors' => $errors,
            'message' => $message ?? "An error encountered while performing the operation",
            'code' => $code,
        ], $code);
    }
    protected function responseDelete(string $message = null, int $code = 200)
    {
        return response()->json([
            'message' => $message ?? "Deleted successfully",
            'code' => $code,
        ], $code);
    }
    protected function returnData($message = "",  $key , $value)
    {
        return response()->json([
            'status' => true,
             // 'errnum' =>"s00",

            'message' => $message ?? "returndata is successfully",
            $key => $value,
        ]);
    }

}