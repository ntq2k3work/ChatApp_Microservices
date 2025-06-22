<?php

namespace App\Http\Services;

use App\Jobs\ProcessSendMail;
use App\Models\JwtToken;
use App\Models\User;
use Illuminate\Container\Attributes\Auth;
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

    public function logout()
    {
        try {
            $jti = auth()->payload()->get('jti');
            JwtToken::where('jti', $jti)->delete();
            JWTAuth::invalidate(JWTAuth::getToken());
            Log::info('User logged out successfully');
            return true;
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not log out'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function refresh()
    {
        try {
            $oldPayload = JWTAuth::parseToken()->getPayload();
            $oldJti = $oldPayload->get('jti');
            $userId = $oldPayload->get('sub');
            $newToken = JWTAuth::parseToken()->refresh();

             $newPayload = JWTAuth::setToken($newToken)->getPayload();
            $newJti = $newPayload->get('jti');
            $expiresAt = now()->addMinutes(config('jwt.ttl'));

            JwtToken::create([
                'user_id' => $userId,
                'jti' => $newJti,
                'device_name' => request()->header('User-Agent'),
                'expired_at' => $expiresAt,
            ]);

            JwtToken::where('jti', $oldJti)->delete();

            Log::info('Token refreshed successfully');
            return $newToken;
        }catch (JWTException $e) {
            return response()->json(['error' => 'Could not refresh token'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function resetPassword(User $user, $newPassword)
    {
        try {
            $currentJti = JWTAuth::parseToken()->getPayload()->get('jti');
            JwtToken::logoutWithoutThisDevice($currentJti);
            $user->password = bcrypt($newPassword);
            $user->save();
            $token = JWTAuth::fromUser($user);  
            Log::info('Password reset successfully for user: ' . $user->email);
            return $token;
        } catch (\Exception $e) {
            Log::error('Error resetting password for user: ' . $user->email . ' ' . $e->getMessage());
            return response()->json(['error' => 'Could not reset password'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function validateOldPassword($email, $oldPassword)
    {
        $user = User::where('email', $email)->first();
        if (!$user || !password_verify($oldPassword, $user->password)) {
            Log::error('Invalid old password for user: ' . $email);
            return response()->json(['error' => 'Invalid old password'], Response::HTTP_UNAUTHORIZED);
        }
        return $user;
    }
                
}
