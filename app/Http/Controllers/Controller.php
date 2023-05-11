<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function convertValidator($validator) {
        $errors = $validator->errors()->all();
        $violations = array();
        foreach ($errors as $error) {
            $string = explode("|", $error);
            $violations[$string[0]] = ['message' => $string[1]];
        }
        return response(['message' => 'request body is not valid', 'violations' => $violations], 400)->header('Content-Type', 'application/json');
    }
    
    public function validatorMessages() {
        return [
            'required' => ':attribute|required',
            'unique' => ':attribute|is already taken',
            'min' => ':attribute|must be at least :min characters long',
            'max' => ':attribute|must be at most :max character long',
            'in' => ':attribute|must be one of: :values'
        ];
    }
}
