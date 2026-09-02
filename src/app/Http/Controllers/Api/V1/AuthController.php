<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Mencari role berdasarkan nama yang tersimpan di database, tanpa asumsi ID.
     */
    protected function findRoleByName(string $roleName): ?Role
    {
        $normalizedName = Role::normalize(trim($roleName));

        return Role::query()
            ->whereRaw('LOWER(nama_role) = ?', [strtolower($normalizedName)])
            ->first();
    }

    /**
     * codingan untuk membuat URL redirect Google OAuth.
     */
    public function googleRedirect(): JsonResponse
    {
        $query = http_build_query([
            'client_id' => config('services.google.client_id'),
            'redirect_uri' => config('services.google.redirect'),
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'access_type' => 'offline',
            'prompt' => 'consent',
        ]);

        $redirectUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . $query;

        return response()->json(['redirect_url' => $redirectUrl]);
    }

    /**
     * codingan untuk menerima callback Google OAuth dan membuat atau memperbarui user.
     */
    public function googleCallback(Request $request)
    {
        $code = $request->input('code');

        if (! $code) {
            return response()->json([
                'message' => 'Authorization code not found.',
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'authorization_code',
                'client_id' => config('services.google.client_id'),
                'client_secret' => config('services.google.client_secret'),
                'redirect_uri' => config('services.google.redirect'),
                'code' => $code,
            ]);

            if (! $tokenResponse->successful()) {
                throw new \Exception('Google token exchange failed.');
            }

            $accessToken = $tokenResponse->json('access_token');
            $userResponse = Http::withToken($accessToken)
                ->get('https://www.googleapis.com/oauth2/v3/userinfo');

            if (! $userResponse->successful()) {
                throw new \Exception('Google userinfo request failed.');
            }

            $socialUser = $userResponse->json();
        } catch (\Exception $exception) {
            Log::error('Google OAuth callback failed', ['error' => $exception->getMessage()]);

            return response()->json([
                'message' => 'Unable to authenticate with Google.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $email = strtolower(trim((string) ($socialUser['email'] ?? '')));

        if ($email === '') {
            return response()->json([
                'message' => 'Google account email is missing.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = User::query()
            ->whereRaw('LOWER(email) = ?', [$email])
            ->first();

        $accountCreated = false;

        if (! $user) {
            $guestRole = $this->findRoleByName(Role::GUEST);

            if (! $guestRole) {
                return response()->json([
                    'message' => 'Guest role is missing in roles table. Please seed roles before Google login.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $user = User::query()->create([
                'id_role' => $guestRole->id_role,
                'nama' => $socialUser['name'] ?? explode('@', $email)[0],
                'email' => $email,
                'password' => Hash::make(Str::random(32)),
                'google_id' => $socialUser['sub'] ?? null,
                'foto' => $socialUser['picture'] ?? null,
                'status' => 'Aktif',
            ]);

            $accountCreated = true;
        }

        $user->update([
            'google_id' => $socialUser['sub'] ?? $user->google_id,
            'foto' => $socialUser['picture'] ?? $user->foto,
        ]);

        $user->load('role');

        $token = $user->createToken('auth-token')->plainTextToken;
        $frontendUrl = env('FRONTEND_URL', 'http://localhost:5173');

        return redirect()->away($frontendUrl . '/auth/callback?' . http_build_query([
            'token' => $token,
            'account_created' => $accountCreated ? '1' : '0',
        ]));
    }

    /**
     * codingan untuk mendaftarkan user baru dengan email dan password.
     */
    public function register(AuthRegisterRequest $request): JsonResponse
    {
        $guestRole = $this->findRoleByName(Role::GUEST);

        if (! $guestRole) {
            return response()->json([
                'message' => 'Guest role is missing in roles table. Please seed roles before creating a user.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // codingan untuk menyimpan user baru ke database.
        $user = User::query()->create([
            'id_role' => $guestRole->id_role,
            'nama' => $request->input('nama'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'status' => 'Aktif',
        ]);

        return response()->json([
            'user' => new UserResource($user),
            'access_token' => $user->createToken('auth-token')->plainTextToken,
            'token_type' => 'Bearer',
        ], Response::HTTP_CREATED);
    }

    /**
     * codingan untuk autentikasi user via email atau nama dan mengembalikan token.
     */
    public function login(AuthLoginRequest $request): JsonResponse
    {
        $identity = $request->input('identity');
        $password = $request->input('password');

        // codingan untuk mengambil user Aktif sesuai email atau nama.
        $query = User::query()->where('status', 'Aktif');

        if (filter_var($identity, FILTER_VALIDATE_EMAIL) !== false) {
            $query->whereRaw('LOWER(email) = ?', [strtolower(trim($identity))]);
        } else {
            $query->whereRaw('LOWER(nama) = ?', [strtolower(trim($identity))]);
        }

        $user = $query->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            return response()->json(['message' => 'Invalid login credentials.'], Response::HTTP_UNAUTHORIZED);
        }

        $user->load('role');

        // codingan untuk menghasilkan token akses Sanctum jika login berhasil.
        return response()->json([
            'user' => new UserResource($user),
            'access_token' => $user->createToken('auth-token')->plainTextToken,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * codingan untuk logout dan menghapus token akses aktif.
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Mengembalikan data user yang sedang login.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user) {
            $user->load('role');
        }

        return response()->json([
            'user' => $user ? new UserResource($user) : null,
        ]);
    }
}
