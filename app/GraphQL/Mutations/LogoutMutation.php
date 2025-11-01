<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\Auth;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class LogoutMutation extends Mutation
{
    protected $attributes = [
        'name' => 'logout',
        'description' => 'Logout user',
    ];

    public function type(): Type
    {
        return Type::boolean();
    }

    public function resolve($root, $args)
    {
        $user = Auth::user();

        if ($user) {
            $user->tokens()->delete(); // revoke all tokens (for Sanctum)
            return true;
        }

        return false;
    }
}
