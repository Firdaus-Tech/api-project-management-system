<?php

namespace App\Repositories\AuthRepositories;

use App\DTOs\Auth\RegistrationDTO;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegistrationRepository
{
    public static function create(RegistrationDTO $registrationData): User
    {
        return User::query()->create([
            'name' => $registrationData->name,
            'email' => $registrationData->email,
            'password' => Hash::make($registrationData->password),
        ]);
    }
}
