<?php

namespace App\Http\Services;

use App\Jobs\ProcessSendMail;
use App\Models\User;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function create($user_validated)
    {
        $user = User::create($user_validated);
        Log::info('Đã tạo user: ' . $user->email);
        
        try {
            $token = JWTAuth::fromUser($user);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
        Log::info('Thực hiện gửi mail tới ' . $user->email);
        dispatch(new ProcessSendMail($user));
        
        return $user;
    }

    public function login($credentials)
    {

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['token' => $token], Response::HTTP_OK);
    }
}
