<?php

namespace App\Http\Controllers;

use App\Http\Services\EmailVerificationService;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    protected $service;

    public function __construct(EmailVerificationService $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $request, $id, $hash)
    {
        $result = $this->service->verifyUserByLink($id, $hash);

        return response()->json(['message' => $result['message']], $result['code']);
    }
}
