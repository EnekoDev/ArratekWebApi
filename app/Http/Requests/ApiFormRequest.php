<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class ApiFormRequest extends FormRequest {
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            'Error' => "Error de validacion",
            'Errors' => $validator->errors()->all()
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
