<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Helpers;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Mail;



class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function newmail()
    {
        // dd('pls, code here');
        $email = "heenasavaliya2704@gmail.com";
        $fromEmail = "transferhorizon@gmail.com";
        $fromName = "New mail trasfer";
        $message = "<p>Hi" . "\r\n";
        $message .= "<p>Thank You" . "</p>\r\n";

        // dd($email,$fromEmail,$fromName,$message);

        \Mail::send([], [], function ($mail) use ($email, $fromEmail, $fromName, $message) {
            $mail->to($email);
            $mail->from($fromEmail, $fromName)
                ->subject('new-mail testing')
                ->setBody($message, 'text/html'); // for HTML rich messages
        });

        dd("this one is testing purpose");
    }

    public function getAuthenticatedUser()
    {
        $response = Helpers::getResponce();
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if ($user) {
                $response['message'] = "Get User Details";
                $response['success'] = 1;
                $response['data'] = $user;
            } else {
                $response['message'] = "User not found";
                $response['success'] = -1;
                $response['data'] = null;
            }
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }
        return response()->json($response);
    }

    public function ChangePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current' => 'required',
            'password' => 'required|min:6',
            'password_confirmation' => 'required_with:password|same:password|min:6',
        ]);
        if ($validator->fails()) {
            $response['success'] = 0;
            $response['message'] = $validator->errors()->first();
        }else{
            try {
                $user = JWTAuth::parseToken()->authenticate();
                $response = Helpers::getResponce();
                if($request->current == $request->password){
                    $response['message'] = 'Do not use current password as a new password';
                    $response['success'] = 0;
                }else{
                    if (!Hash::check($request->current, $user->password)) {
                        $response['message'] = 'Current password does not match!';
                        $response['success'] = 0;
                    } 
                    else {
                        $user->password = Hash::make($request->password);
                        $user->save();
                        $user_profile = User::find($user->id);
                        $response['message'] = "Update Password Successfully";
                        $response['success'] = 1;
                    }
                }
            } catch (Exception $e) {
                $response['message'] = $e->getMessage();
            }
        }
        return response()->json($response);
    }


    public function login(Request $request)
    {
        $data = $request->all();
        $response = Helpers::getResponce();
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required|string|min:6',
            ]);
            if ($validator->fails()) {
                $response['message'] = $validator->errors()->first();
            } else {
                $user = User::where('email', $request->email)->first();
                if ($user != null) {
                    if ($request->has(['email', 'password'])) {
                        $credentials = $request->only('email', 'password');
                    } else {
                        $credentials = $request->only('email', 'password');
                    }
                    try {
                        if (!$token = JWTAuth::attempt($credentials)) {
                            $response['message'] = "Invalid Credentials, username and password dismatches. Or username may not registered.";
                            $response['success'] = -1;
                        } else {
                            $response['message'] = "Login SuccessFully";
                            $response['success'] = 1;
                            $response['data'] = $user;
                            $response['data']['token'] = $token;
                        }
                    } catch (JWTException $e) { // something went wrong whilst attempting to encode the token
                        return response()->json(['error' => 'could_not_create_token'], 500);
                    }
                } else {
                    $response['message'] = "Invalid username or password";
                    $response['success'] = 0;
                }
            }
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }
        return response()->json($response);
    }

    public function register(Request $request)
    {
        $response = Helpers::getResponce();
        try {
            $data = $request->all();
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                // 'password_confirmation' => 'required_with:password|same:password|min:6',
                'profile_image' => 'mimes:jpeg,jpg,png',
            ]);
            if ($validator->fails()) {
                $response['message'] = $validator->errors()->first();
            } else {
                $profile_image = "default.png";
                if (isset($data['profile_image']) && $data['profile_image'] != null) {
                    $profile_image = Helpers::upload_image($data['profile_image'], config('constants.user_profile'));
                }
                $user = User::create([
                    'first_name' => trim($data['first_name']),
                    'last_name' => trim($data['last_name']),
                    'email' => trim($data['email']),
                    'password' => Hash::make($data['password']),
                    'device_token' => $data['device_token'],
                    'profile_image' => $profile_image,
                    //'device_arn' => $device_arn,
                ]);
                if ($user) {
                    $token = JWTAuth::fromUser($user);
                    $response['message'] = "Registration completed successfully";
                    $response['success'] = 1;
                    $response['data'] = $user;
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
