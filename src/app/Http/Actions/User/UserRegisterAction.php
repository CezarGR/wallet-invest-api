<?php

namespace App\Http\Actions\User;

use App\Http\Requests\User\UserRegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserRegisterAction 
{
    public function __invoke(UserRegisterRequest $request)
    {
        try 
        {
            $user = User::create([
                'name' => $request->get('name'),
                'document' => $request->get('document'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
            ]);

            $token = $user->createToken("token-user-$user->id");

            return response()->json([
                'token' => $token->plainTextToken,
                'user' => $user
            ]);
        }
        catch (Exception $exception) 
        {
            dd($exception);
        }
    }
}