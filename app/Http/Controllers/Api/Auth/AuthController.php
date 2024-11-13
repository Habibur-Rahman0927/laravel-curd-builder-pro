<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ApiLoginRequest;
use App\Http\Requests\CreateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use App\Services\User\IUserService;
use Exception;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function __construct(private IUserService $userService)
    {

    }
    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Authentication"},
     *     summary="Register a new user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "user_type", "role_id"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *             @OA\Property(property="user_type", type="string", example="1"),
     *             @OA\Property(property="role_id", type="string", example="1"),
     *         ),
     *     ),
     *     @OA\Response(response=201, description="User registered successfully"),
     *     @OA\Response(response=400, description="Invalid input"),
     * )
     */
    public function register(CreateUserRequest $request)
    {
        $requestData = $request->all();
        $requestData['password'] = Hash::make($request->password);
        $user = $this->userService->create($requestData);

        if (Role::where('id', $request->role_id)->exists()) {
            $role = Role::find($request->role_id);
            $user->assignRole($role->name);
        } else {
            return response()->json(['error' => 'Role does not exist.']);
        }
        
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Authentication"},
     *     summary="Login a user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="admin@admin.com"),
     *             @OA\Property(property="password", type="string", example="123456")
     *         ),
     *     ),
     *     @OA\Response(response=200, description="Login successful"),
     *     @OA\Response(response=401, description="Unauthorized"),
     * )
     */
    public function login(ApiLoginRequest $request)
    {
        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
    
            $user = Auth::user();
    
            $token = $user->createToken('authToken')->plainTextToken;
    
            return response()->json([
                'user' => $user,
                'token' => $token,
                'message' => 'Login successful'
            ], 200);
    
        } catch (HttpResponseException $e) {
            return response()->json(['message' => 'Invalid request.'], 400);
    
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred during login. Please try again later.'
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     summary="Logout a user",
     *     @OA\Response(response=200, description="Logout successful")
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/refresh",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     summary="Refresh access token",
     *     @OA\Response(response=200, description="Token refreshed successfully"),
     * )
     */
    public function refreshToken(Request $request)
    {
        $user = $request->user();
        $request->user()->tokens()->delete();
        $newToken = $user->createToken('authToken')->plainTextToken;

        return response()->json(['token' => $newToken], 200);
    }
}
