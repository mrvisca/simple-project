<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cabang;
use App\Models\Bisnis;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
        * @OA\Post(
        * path="/v1/otentikasi/masuk",
        * operationId="Login",
        * tags={"Autentikasi"},
        * summary="User Login",
        * description="Login pengguna qollega (owner, pic, kasir, staff)",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"email","password"},
        *               @OA\Property(property="email", type="email", description="Akun email aktif yang sudah di aktivasi"),
        *               @OA\Property(property="password", type="password", description="Password akun"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Login Berhasil",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=401, description="Gagal login, akun belum diverifikasi / Akun anda belum terdaftar di sistem kami"),
        * )
        */
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $fields['email'])->first();

        if ($user == null) {
            return response([
                'message' => 'Akun anda belum terdaftar di sistem kami'
            ], 401);
        }

        if($user->email_verified_at == null)
        {
            return response()->json([
                'message' => 'Gagal login, akun belum diverifikasi',
                'email_verified_at' => $user->email_verified_at,
            ],401);
        }

        $checkPass = User::where("password", "!=", Hash::check($fields['password'], $user->password))->first();

        if ($checkPass == null) {
            return response([
                'message' => 'Password anda Salah'
            ], 401);
        }

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Gabungan antara email dan password salah'
            ], 401);
        }

        $tokenResult = $user->createToken('Access Token');
        $token = $tokenResult->plainTextToken;

        return response()->json([
            'id' => $user->id,
            'role_id' => $user->role_id,
            'role_name' => $user->role->name ?? '',
            'bisnis_id' => $user->bisnis_id,
            'bisnis_name' => $user->bisnis->name ?? '',
            'cabang_id' => $user->cabang_id,
            'cabang_name' => $user->cabang->name ?? '',
            'name' => $user->fullname,
            'email' => $user->email,
            'token' => $token,
        ],200);
    }

    /**
        * @OA\Post(
        * path="/v1/otentikasi/daftar",
        * operationId="Pendaftaran Akun Merchant",
        * tags={"Autentikasi"},
        * summary="Pendaftaran Akun",
        * description="Pendaftaran Akun Merchant",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"name","email","password"},
        *               @OA\Property(property="name", type="text", description="Nama Lengkap Pengguna"),
        *               @OA\Property(property="email", type="text", description="Alamat Email yang akan di daftarkan"),
        *               @OA\Property(property="password", type="password", description="Password Pengguna"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Berhasil mendaftarkan akun, silahkan login",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=400,
        *          description="Pendaftaran gagal, periksa kembali data validasi anda",
        *          @OA\JsonContent()
        *       )
        * )
        */
    public function register(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'name'   => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required',
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $pengguna = new User();
        $pengguna->role_id = 2;
        $pengguna->bisnis_id = 1;
        $pengguna->cabang_id = 1;
        $pengguna->name = $request->name;
        $pengguna->email = $request->email;
        $pengguna->password = Hash::make($request->password);
        $pengguna->email_verified_at = date('Y-m-d H:i:s');
        $pengguna->save();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendaftarkan akun, silahkan login',
        ],201);
    }
}
