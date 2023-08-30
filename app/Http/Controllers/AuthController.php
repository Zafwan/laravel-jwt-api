<?php

namespace App\Http\Controllers;

use App\Models\User;
use Tymon\JWTAuth\JWT;
use Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\API\BaseController as BaseController;
use OpenApi\Annotations as OA;

class AuthController extends BaseController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * @OA\Tag(
     *     name="Authentication",
     *     description="Endpoints related to user authentication"
     * )
     */

    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="Register a new user",
     *     tags={"Authentication"},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="User's name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="User's email",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User's password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password_confirmation",
     *         in="query",
     *         description="Password confirmation",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="201", description="User registered successfully"),
     *     @OA\Response(response="422", description="Validation errors")
     * )
     */
    public function register()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            // return response()->json($validator->errors()->toJson(), 400);
            return $this->sendValidationError('Validation Error', $validator->errors());
        }

        $user = new User;
        $user->name = request()->name;
        $user->email = request()->email;
        $user->password = bcrypt(request()->password);
        $user->save();

        // return response()->json($user, 201);
        return $this->sendResponseRegister($user, 'New User Created Successfully.');
    }

    /**
     * @OA\Tag(
     *     name="Authentication",
     *     description="Endpoints related to user authentication"
     * )
     */

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="Authenticate user and generate JWT token",
     *     tags={"Authentication"},
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="User's email",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User's password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Login successful"),
     *     @OA\Response(response="401", description="Invalid credentials")
     * )
     */
    public function login()
    {
        //old code
        // $credentials = request(['email', 'password']);

        // if (! $token = auth()->attempt($credentials)) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }

        // return $this->respondWithToken($token);
        //old code

        //new code
        $credentials = request(['email', 'password']);

        if (!$token = JWTAuth::attempt($credentials)) {
            // return response()->json(['error' => 'Unauthorized'], 401);

            return $this->sendLoginError('Unauthorized', '');
        }

        return $this->respondWithToken($token);
    }

    /**
     * @OA\Tag(
     *     name="Authentication",
     *     description="Endpoints related to user authentication"
     * )
     */

    /**
     * @OA\Post(
     *     path="/api/auth/me",
     *     summary="Get logged-in user details",
     *     tags={"Authentication"},
     *     @OA\Response(response="200", description="Success"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function me()
    {
        $user = auth()->user();
        $data = response()->json($user);

        return $this->sendResponse($data, 'Login User Retrieved Successfully');
    }

    /**
     * @OA\Tag(
     *     name="Authentication",
     *     description="Endpoints related to user authentication"
     * )
     */

    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     summary="Log the user out (Invalidate the token)",
     *     tags={"Authentication"},
     *     @OA\Response(response="200", description="Success"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function logout()
    {
        auth()->logout();

        // return response()->json(['message' => 'Successfully logged out']);

        return $this->sendResponse([], 'Logged out Successfully');
    }

    /**
     * @OA\Tag(
     *     name="Authentication",
     *     description="Endpoints related to user authentication"
     * )
     */

    /**
     * @OA\Post(
     *     path="/api/auth/refresh",
     *     summary="Refresh the token",
     *     tags={"Authentication"},
     *     @OA\Response(response="200", description="Success"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $data = response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);

        return $this->sendResponse($data, 'Login Successfully');
    }
}