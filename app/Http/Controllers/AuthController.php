<?php

namespace App\Http\Controllers;

use App\Exceptions\BadRequestException;

use App\Helper\ResponseHelper;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\AdminService;
use App\Services\AlumniService;
use App\Services\AuthService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    private AuthService $authService;
    private UserService $userService;


    public function __construct()
    {
        $this->authService = new AuthService();
        $this->userService = new UserService();
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validate($request->rules(), $request->messages());

        return $this->authService->login($request->input('emailOrNik'), $request->input('password'));
    }



    public function registerUser(RegisterRequest $request)
    {
        $request->validate($request->rules(), $request->messages());

        $requestData = $request->all();
        $isRegister = $this->authService->registerUser($requestData);
        if ($isRegister) {
            return response()->json(
                [
                    'status' => true,
                    'code' => 201,
                    'message' => 'Berhasil Registrasi silahkan login',
                    'data' => $isRegister
                ],
                201,
                ['Content-type' => 'application/json']
            );
        } else {
            return response()->json(
                [
                    'status' => false,
                    'code' => 400,
                    'message' => 'Gagal registrasi terjadi kesalahan',
                    'data' => $isRegister
                ],
                400,
                ['Content-type' => 'application/json']
            );
        }
    }


    public function updateEmailVerified(Request $request)
    {
        $id = $request->get('id');
        return $this->authService->updateVeriviedEmail($id);
    }


    public function verifikasiEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);
        if ($validator->fails()) {
            throw new BadRequestException($validator->errors()->first());
        }
        $response = $this->authService->verifikasiEmail($request->all());
        return ResponseHelper::successResponse($response['message'], $response['data'], $response['code']);
    }


    public function verifikasi(Request $request)
    {

        $service = new AlumniService();
        return $service->updateDataAlumni();
    }

    public function checkAlumniData()
    {
        $service = new AlumniService();
        return $service->findAllAlumni();
    }

    public function recovery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);
        if ($validator->fails()) {
            throw new BadRequestException($validator->errors()->first());
        }
        $response = $this->authService->sendRecovery($request->input('email'));
        return ResponseHelper::successResponse($response['message'], $response['data'], $response['code']);

        // $this->userService->recovery();
    }

}