<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
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
}
