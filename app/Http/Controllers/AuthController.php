<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ], [
                'email.required' => 'Email tidak boleh kosong',
                'email.email' => 'Gunakan format email dengan benar',
                'password.required' => 'Password tidak boleh kosong',
            ]);

            $credentials = $request->only('email', 'password');

            if (! $token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $refreshToken = Str::random(60);

            $user = Auth::user();
            $user->refresh_token = $refreshToken;
            $user->save();

            return response()->json([
                'name' => $user->name,
                'email' => $user->email,
                'accessToken' => $token,
                'refreshToken' => $refreshToken,
                'status' => 200,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Error Token'], 401);
        } catch (\Throwable $e) {
            //throw $th;
            return response()->json(['error' => 'Login failed'], 500);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        $providedRefreshToken = $request->input('refreshToken');

        $user = User::findByRefreshToken($providedRefreshToken);

        if (!$user) {
            return response()->json(['error' => 'Invalid refresh token'], 401);
        }

        try {
            $newToken = auth()->refresh();
            $newRefreshToken = Str::random(60);
            $user->refresh_token = $newRefreshToken;
            $user->save();

            return response()->json([
                'accessToken' => $newToken,
                'refreshToken' => $newRefreshToken,
                'message' => 'Refresh Token Berhasil',
                'status' => 200,
            ], 200);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to refresh token'], 401);
        }
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
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
