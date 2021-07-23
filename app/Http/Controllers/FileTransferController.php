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
use Illuminate\Support\Str;

class FileTransferController extends Controller
{

    public function resetPasswordSubmit(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|min:6|same:password'
        ]);
        $data = $request->all();
        // dd($data);
        try {
            $date = \Carbon\Carbon::now();
            $expired_at = \Carbon\Carbon::parse($date->format("Y-m-d H:i:s"));
            $check_user = User::where('forgot_token', $data['token'])->where('email', $data['email'])->where('expired_at', '>=', $expired_at)->orderBy('id', 'DESC')->first();
            if ($check_user != null) {
                $update_pass = $check_user->update(['password' => Hash::make($data['password']), 'forgot_token' => null, 'expired_at' => null]);
                if ($update_pass) {
                    return view('user.updated_password', ['message' => 'your password has been Reset successfully', 'type' => 'success']);
                }
            }
        } catch (Exception $e) {
            return view('user.updated_password', ['message' => 'Oops, Something went wrong. Your Session has been expired.', 'type' => 'error']);
        }
    }

    public function forgotPassword(Request $request)
    {
        $email = $request['email'];
        $response = Helpers::getResponce();
        // dd($email);
        try {
            $date = \Carbon\Carbon::now();
            $end = $date->modify('+1 day');
            $expired_at = \Carbon\Carbon::parse($end->format("Y-m-d H:i:s"));
            // dump($email);
            $token = str_random(60);
            // dd($token);
            $user = User::where('email', $email)->first();
            if ($user != null || $user != "") {
                // dd($user->id);
                $update_user = $user->update([
                    'forgot_token' => $token,
                    'expired_at' => $expired_at
                ]);
                if ($update_user) {
                    $maildata = [
                        'name' => $user->first_name . ' ' . $user->last_name,
                        'link' => url('/reset-password/' . $email . '/' . $token)
                    ];
                    // dump($maildata);
                    $to = [$email => "TRANSFER"];
                    // dump($to);
                    Helpers::sendMail('reset_password', $maildata, $to, "TRANSFER - Reset Password Link");
                    $response['message'] = "Send Reset Password Link in Your Mail";
                    $response['success'] = 1;
                }
            } else {
                $response['success'] = 0;
                $response['data'] = null;
                $response['message'] = "Invalid Email Address";
            }
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }
        return response()->json($response);
    }

    public function resetPassword($email, $token)
    {
        // dd($email, $token);
        try {
            $date = \Carbon\Carbon::now();
            $expired_at = \Carbon\Carbon::parse($date->format("Y-m-d H:i:s"));
            $check_user = User::where('forgot_token', $token)->where('email', $email)->where('expired_at', '>=', $expired_at)->orderBy('id', 'DESC')->first();
            if ($check_user != null) {
                return view('user.reset_password', ['email' => $email, 'token' => $token]);
            } else {
                return view('user.updated_password', ['message' => 'Oops, Something went wrong. Your Sessino has been expired.', 'type' => 'error']);
            }
        } catch (Exception $e) {
            return view('user.updated_password', ['message' => 'Oops, Something went wrong. Your Sessino has been expired.', 'type' => 'error']);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
