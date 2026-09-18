<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA;

/**
 * @OA\PathItem(
 *     path="/api/user",
 *
 *     @OA\Get(
 *         path="/api/user",
 *         summary="Get all users",
 *         description="Return a list of all registered users.",
 *         tags={"Users"},
 *         security={{"bearerAuth": {}}},
 *
 *         @OA\Response(
 *             response=200,
 *             description="Users retrieved successfully",
 *
 *             @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/User"))
 *         ),
 *
 *         @OA\Response(response=401, description="Unauthorized"),
 *         @OA\Response(response=500, description="Server error")
 *     ),
 *
 *     @OA\Post(
 *         path="/api/user",
 *         summary="Create a new user",
 *         description="Create and store a new user account.",
 *         tags={"Users"},
 *
 *         @OA\RequestBody(
 *             required=true,
 *
 *             @OA\JsonContent(
 *                 required={"name", "email", "password"},
 *
 *                 @OA\Property(property="name", type="string", example="John Doe"),
 *                 @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *                 @OA\Property(property="password", type="string", format="password", example="secret123")
 *             )
 *         ),
 *
 *         @OA\Response(
 *             response=201,
 *             description="User created successfully",
 *
 *             @OA\JsonContent(ref="#/components/schemas/User")
 *         ),
 *
 *         @OA\Response(response=401, description="Unauthorized")
 *     )
 * )
 *
 * @OA\PathItem(
 *     path="/api/user/{id}",
 *
 *     @OA\Get(
 *         path="/api/user/{id}",
 *         summary="Get user by ID",
 *         description="Retrieve a single user by their identifier.",
 *         tags={"Users"},
 *         security={{"bearerAuth": {}}},
 *
 *         @OA\Parameter(
 *             name="id",
 *             in="path",
 *             required=true,
 *             description="User ID",
 *
 *             @OA\Schema(type="integer")
 *         ),
 *
 *         @OA\Response(
 *             response=200,
 *             description="User retrieved successfully",
 *
 *             @OA\JsonContent(ref="#/components/schemas/User")
 *         ),
 *
 *         @OA\Response(response=401, description="Unauthorized")
 *     ),
 *
 *     @OA\Put(
 *         path="/api/user/{id}",
 *         summary="Update a user",
 *         description="Update an existing user record.",
 *         tags={"Users"},
 *         security={{"bearerAuth": {}}},
 *
 *         @OA\Parameter(
 *             name="id",
 *             in="path",
 *             required=true,
 *             description="User ID",
 *
 *             @OA\Schema(type="integer")
 *         ),
 *
 *         @OA\RequestBody(
 *             required=true,
 *
 *             @OA\JsonContent(
 *
 *                 @OA\Property(property="name", type="string", example="John Smith"),
 *                 @OA\Property(property="email", type="string", format="email", example="john.smith@example.com"),
 *                 @OA\Property(property="password", type="string", format="password", example="newsecret123")
 *             )
 *         ),
 *
 *         @OA\Response(
 *             response=200,
 *             description="User updated successfully"
 *         ),
 *         @OA\Response(response=401, description="Unauthorized")
 *     ),
 *
 *     @OA\Delete(
 *         path="/api/user/{id}",
 *         summary="Delete a user",
 *         description="Delete a user record from the database.",
 *         tags={"Users"},
 *         security={{"bearerAuth": {}}},
 *
 *         @OA\Parameter(
 *             name="id",
 *             in="path",
 *             required=true,
 *             description="User ID",
 *
 *             @OA\Schema(type="integer")
 *         ),
 *
 *         @OA\Response(response=204, description="User deleted successfully"),
 *         @OA\Response(response=401, description="Unauthorized")
 *     )
 * )
 *
 * @OA\PathItem(
 *     path="/api/login",
 *
 *     @OA\Post(
 *         path="/api/login",
 *         summary="Login and generate token",
 *         description="Authenticate a user and return a Sanctum bearer token for protected API access.",
 *         tags={"Auth"},
 *
 *         @OA\RequestBody(
 *             required=true,
 *
 *             @OA\JsonContent(
 *                 required={"email", "password"},
 *
 *                 @OA\Property(property="email", type="string", format="email", example="user1@example.com"),
 *                 @OA\Property(property="password", type="string", format="password", example="password1")
 *             )
 *         ),
 *
 *         @OA\Response(
 *             response=200,
 *             description="Login successful",
 *
 *             @OA\JsonContent(
 *
 *                 @OA\Property(property="message", type="string", example="Login successful"),
 *                 @OA\Property(property="token", type="string", example="1|abcxyz123456"),
 *                 @OA\Property(property="user", ref="#/components/schemas/User")
 *             )
 *         ),
 *
 *         @OA\Response(response=401, description="Invalid credentials")
 *     )
 * )
 */
class UserController extends Controller
{
    /**
     * Get all users from the database.
     *
     * This function is used for listing user data in the admin or internal API.
     * It is protected by Sanctum token authentication.
     */
    public function index(): Collection
    {
        return User::all();
    }

    /**
     * Authenticate a user and issue a new bearer token.
     *
     * This endpoint is used to log in with email and password.
     * If the credentials match, Laravel creates a personal access token
     * and returns it to the client for later API requests.
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
        ], 200);
    }

    /**
     * Create a new user account.
     *
     * This method validates the request data, hashes the password,
     * and stores the new user in the database.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        return response()->json(User::create($validated), 201);
    }

    /**
     * Get one specific user by model binding.
     *
     * Laravel resolves the user ID from the URL and fetches the matching record.
     */
    public function show(User $user): JsonResponse
    {
        return response()->json($user, 200);
    }

    /**
     * Update user data.
     *
     * This method accepts partial or full user data and updates the existing record.
     * If a new password is provided, it is hashed before saving.
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes', 'required', 'email', 'unique:users,email,'.$user->id],
            'password' => 'sometimes|required|string|min:8',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user,
        ], 200);
    }

    /**
     * Delete a user record.
     *
     * This removes the existing user from the database and returns a 204 no-content response.
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json(null, 204);
    }
}
