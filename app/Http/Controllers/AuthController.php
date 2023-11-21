<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Service\IAuthService;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct(public IAuthService $authService)
    {
    }

    public function register(RegisterRequest $request)
    {
        $serviceData = $this->authService->register($request->validated());
        return $this->successResponse($serviceData , Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $serviceData = $this->authService->login($data);

        if (isset($serviceData['error'])) {
            return $this->successResponse([
                'message' => $serviceData['error'],
            ] , Response::HTTP_UNAUTHORIZED);
        }
        return $this->successResponse($serviceData , Response::HTTP_OK);
    }

    public function logout(Request $request)
    {
        $serviceData = $this->authService->logout($request);
        if (isset($serviceData['error'])) {
            return response()->json([
                'message' => $serviceData['error'],
            ], Response::HTTP_UNAUTHORIZED);
        }   
        return $this->successResponse($serviceData , Response::HTTP_OK);
    }
    
}
