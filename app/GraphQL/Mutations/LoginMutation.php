<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class LoginMutation extends Mutation
{
    protected $attributes = [
        'name' => 'login',
        'description' => 'Login user and get token',
    ];

    public function type(): Type
    {
        return Type::string(); // Return JWT or Sanctum token
    }

    public function args(): array
    {
        return [
            'email' => ['type' => Type::nonNull(Type::string())],
            'password' => ['type' => Type::nonNull(Type::string())],
        ];
    }

    public function resolve($root, $args)
    {
        $user = User::where('email', $args['email'])->first();

        if (! $user || ! Hash::check($args['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials'],
            ]);
        }

        // if using Laravel Sanctum:
        $token = $user->createToken('auth_token')->plainTextToken;

        return $token;
    }
}
