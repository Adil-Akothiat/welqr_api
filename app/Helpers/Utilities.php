<?php 

namespace App\Helpers;

use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Response;
use Exception;

class Utilities {
    public static function errorsHandler(Exception $e) {
        if ($e instanceof ValidationException) {
            return response()->json([
                'message' => $e->errors()
            ], 422)->header('Content-Type', 'application/json');;
        }
        if ($e instanceof QueryException) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500)->header('Content-Type', 'application/json');;
        }

        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'message' => $e->getMessage()
            ], 404)->header('Content-Type', 'application/json');;
        }
        return response()->json([
            'message' => $e->getMessage()
        ], 400)->header('Content-Type', 'application/json');
    }
}