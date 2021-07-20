<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Helpers;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use JWTAuth;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function register(Request $request)
    {
        $response = Helpers::getResponce();
        try {
            $data = $request->all();
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'profile_image' => 'max:10000|mimes:jpeg,jpg,png',
            ]);

            if ($validator->fails()) {
                $response['message'] = $validator->errors()->first();
            } else {
                $profile_image = "default.png";
                // if (isset($data['profile_image']) && $data['profile_image'] != null) {
                //     $profile_image = Helpers::upload_image($data['profile_image'], config('constants.profile_image'));
                // }
                $user = User::create([
                    'first_name' => trim($data['first_name']),
                    'last_name' => trim($data['last_name']),
                    'email' => trim($data['email']),
                    'password' => Hash::make($data['password']),
                    'device_token' => $data['device_token'],
                    // 'profile_image' => $profile_image,
                    //'device_arn' => $device_arn,
                ]);
                if ($user) {
                    $token = JWTAuth::fromUser($user);
                    $response['message'] = "registration completed successfully";
                    $response['success'] = 1;
                    $response['data']['user'] = $user;
                    $response['data']['token'] = $token;
                } else {
                    $response['message'] = "Something went wrong";
                    $response['success'] = 0;
                }
            }
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }
        return response()->json($response);
    }


    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
