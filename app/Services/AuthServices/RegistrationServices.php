<?php

namespace App\Services\AuthServices;

use App\DTOs\Auth\RegistrationDTO;
use App\Models\User;
use App\Repositories\AuthRepositories\RegistrationRepository;
use Illuminate\Support\Facades\DB;
use Throwable;

class RegistrationServices
{
    /**
     * Create a new user account and generate an authentication token.
     *
     * @param RegistrationDTO $registrationData
     * @return array<string, mixed>
     */
    public static function create(RegistrationDTO $registrationData): array
    {
        try {
            return DB::transaction(function () use ($registrationData) {
                $user = RegistrationRepository::create($registrationData);
                $authToken = self::token($user);

                return [
                    'success' => true,
                    'user' => $user,
                    'token' => $authToken,
                ];
            });
        } catch (Throwable $e) {
            return [
                'success' => false,
                'user' => null,
                'token' => null,
                'message' => $e->getMessage(),
            ];
        }
    }

    public static function token(User $user): string
    {
        return $user->createToken('api-token')->plainTextToken;
    }
}
