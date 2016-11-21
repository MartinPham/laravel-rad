<?php
/**
 * Created by PhpStorm.
 * User: zzz
 * Date: 21/11/16
 * Time: 12:50
 */

namespace App\RAD\Helpers;


class Util
{
    public static function handleAPIException($request, $exception)
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

        // Define the response
        $response = [
//                'errors' => 'Sorry, something went wrong.',
            'ok' => false,
            'code' => 500,
        ];

        // If the app is in debug mode
        if (config('app.debug'))
        {
            // Add the exception class name, message and stack trace to response
            $response['e'] = get_class($exception); // Reflection might be better here
            $response['msg'] = $exception->getMessage();
            $response['trace'] = $exception->getTrace();
        }

        // Default response of 400
        $status = 200;


        // If this exception is an instance of HttpException
        // if ($this->isHttpException($e))
        // {
        //     // Grab the HTTP status code from the Exception
        //     $status = $e->getStatusCode();
        // }

        // Return a JSON response with the response array and status code
        return response()->json($response, $status);

//            $response = Response::json($response, $status);
////            $response->header('Content-Type', 'text/plain');
//            $response->header('Content-Type', 'application/json');
//            return $response;
    }
}