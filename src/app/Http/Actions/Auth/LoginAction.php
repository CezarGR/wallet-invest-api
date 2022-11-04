<?php

namespace App\Http\Actions\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginAction 
{
    public function __invoke(LoginRequest $request)
    {
        try 
        {
            $user = User::where('email', $request->email)->first();
 
            if (! $user || ! Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }
         
            return response()->json([
                'token' => $user->createToken("token-user-$user->id")->plainTextToken,
                'user' => $user
            ]);
        }
        catch (Exception $exception) 
        {
            dd($exception);
        }
    }
}