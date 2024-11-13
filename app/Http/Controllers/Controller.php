<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
* @OA\Info(
*     title="CRUD Builder API Documentation",
*     version="1.0.0",
*     description="API documentation for the CRUD Builder, designed to generate CRUD operations dynamically for various models and configurations.",
*     @OA\Contact(
*         email="support@yourcrudbuilder.com"
*     ),
*     @OA\License(
*         name="Apache 2.0",
*         url="http://www.apache.org/licenses/LICENSE-2.0.html"
*     )
* ),
* @OA\SecurityScheme(
*     type="http",
*     securityScheme="bearerAuth",
*     scheme="bearer",
*     bearerFormat="JWT"
* )
*/
abstract class Controller
{
    use AuthorizesRequests, ValidatesRequests;
    
    /**
     * @param array $result
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function success($result, string $message, int $code = Response::HTTP_OK): JsonResponse
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];
        return response()->json($response, $code);
    }

    /**
     * @param string $errorMessage
     * @param array $errors
     * @param int $code
     * @return JsonResponse
     */
    public function error(string $errorMessage, array $errors = [], int $code = Response::HTTP_UNPROCESSABLE_ENTITY): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $errorMessage,
            'errors' => $errors
        ];
        return response()->json($response, $code);
    }
}
