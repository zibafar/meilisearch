<?php


namespace App\Traits;


use Illuminate\Http\JsonResponse;

trait SendJsonResponse
{
    /**
     * success response method.
     *
     * @param $result
     * @param $message
     * @return JsonResponse
     */
    public function sendResponse($result, $message, $ability = 'public'): JsonResponse
    {
        if (!is_array($result)) {
            $result = ['result' => $result];
        }

        $response = [
                'ability' => $ability,
                'success' => true,
                'message' => $message,

            ] + $result;


        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @param array|bool $array
     * @param int $code
     * @return JsonResponse
     */
    public function sendErrorFromArray(array|bool $array,int $code = 404): JsonResponse
    {
        return $this->sendError(
            $array['error'] ?? 'error',
            [
                'error' => $array['msg'] ?? __('all.error-unknown')
            ],
            $code
        );
    }


    /**
     * return error response.
     *
     * @param string $error
     * @param array $errorMessages
     * @param int $code
     * @return JsonResponse
     */
    public function sendError(string $error,array $errorMessages = [],int $code = 404):JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];


        if (!empty($errorMessages)) {
            $response = $response + $errorMessages;
        }


        return response()->json($response, $code);
    }
}
