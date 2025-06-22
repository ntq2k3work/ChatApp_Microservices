<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class EmailVerificationService
{
    public function verifyUserByLink($id, $hash): array
    {
        $user = User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return [
                'status' => false,
                'message' => 'Liên kết xác thực không hợp lệ.',
                'code' => 403
            ];
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            
            Log::info('User verified email: ' . $user->email);
        }

        return [
            'status' => true,
            'message' => 'Email xác thực thành công.',
            'code' => 200
        ];
    }
}
