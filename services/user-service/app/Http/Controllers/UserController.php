<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Http\Request;
use App\Http\Services;
use App\Http\Services\UserService;
use Illuminate\Http\Response;
class UserController extends Controller
{

    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

   public function register(CreateUserRequest $request)
   {
        $user_validated = $request->validated();
        $user = $this->userService->create($user_validated);

        if(!$user) {
            return Response()->json([
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'User creation failed'
            ], status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return Response()->json([
            'success' => true,
            'status' => Response::HTTP_CREATED,
            'message' => 'User created successfully'
        ], status: Response::HTTP_CREATED);
   }

   public function login(LoginRequest $request)
   {
        $credentials = $request->validated();
        $user = $this->userService->login($credentials);

        if (!$user) {
            return Response()->json([
                'success' => false,
                'status' => Response::HTTP_UNAUTHORIZED,
                'message' => 'Invalid credentials'
            ], status: Response::HTTP_UNAUTHORIZED);
        }

        return Response()->json([
            'success' => true,
            'status' => Response::HTTP_OK,
            'message' => 'Login successful',
            'data' => $user
        ], status: Response::HTTP_OK);
   }

   public function logout(Request $request)
   {
        try {
            $this->userService->logout();
            return response()->json([
                'success' => true,
                'status' => Response::HTTP_OK,
                'message' => 'Logout successful'
            ], status: Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Logout failed'
            ], status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
   }

   public function refresh(Request $request)
   {
        $newToken = $this->userService->refresh();
        return response()->json([
            'success' => true,
            'token' => $newToken,
            'status' => Response::HTTP_OK,
            'expires_in' => auth()->factory()->getTTL() * 60
        ], status: Response::HTTP_OK);
   }

   public function resetPassword(ResetPasswordRequest $request)
   {
        $validatedData = $request->validated();
        $email = $validatedData['email'];
        $newPassword = $validatedData['new_password'];

        $newToken = $this->userService->resetPassword($email, $newPassword);

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully',
            'token' => $newToken,
            'expires_in' => auth()->factory()->getTTL() * 60,
            'status' => Response::HTTP_OK
        ], status: Response::HTTP_OK);
   }
}
