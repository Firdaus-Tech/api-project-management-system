<?php

namespace App\Http\Controllers;

use App\DTOs\Auth\RegistrationDTO;
use App\Helpers\ApiResponse;
use App\Http\Requests\RegisterationValidation;
use App\Services\AuthServices\RegistrationServices;
use Illuminate\Http\JsonResponse;

class RegistrationController extends Controller
{
    public function register(RegisterationValidation $request): JsonResponse
    {

        $data = new RegistrationDTO(
            $request->name,
            $request->email,
            $request->password,
        );
        $result = RegistrationServices::create($data);

        return ApiResponse::handle($result);
    }
}
