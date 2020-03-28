<?php


namespace App\Traits;


use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait ApiRequestResponse{

    protected function errorRequestApi(Validator $validator){
        $errors = (new ValidationException($validator))->errors();
        $message = (new ValidationException($validator))->getMessage();
        throw new HttpResponseException(response()->json(['data' => ['message' => $message, 'errors' => $errors]], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }

}
