<?php

namespace App\Http\Controllers;

use App\Models\Voyage;
use Exception;
use Illuminate\Http\Request;
use App\Http\Helpers;
use App\Models\User;
use App\Models\TransferInfo;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Config;

class VoyageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function CreateTransferData(Request $request)
    {
        // dump('wel-come to function');
        $response = Helpers::getResponce();
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                //    'emails' => 'required',
                'receiver_id' => 'required',
                'contents' => 'required'
            ]);
            if ($validator->fails()) {
                // dd("validation");   
                // dd($validator->fails());
                $response['message'] = $validator->errors()->first();
            } else {

                $emails = [];
                $me = JWTAuth::parseToken()->authenticate();

                if ($me != null) {
                    $receiver_ids = (isset($data['receiver_id']) && $data['receiver_id'] != null) ? $data['receiver_id'] : null;
                    $ids = ($receiver_ids != null) ? explode(",", $receiver_ids) : [];
                    $contents = (isset($data['contents']) && $data['contents'] != null) ? $data['contents'] : null;
                    // dump($contents);

                    if ($contents != null) {
                        // foreach ($contents as $c) {

                        //     $extension = $c['file']->getClientOriginalExtension();
                        //     $img = Helpers::upload_image($c['file'], config('constants.file_data'));
                        //     $create_file = TransferInfo::create([
                        //         'type' => $extension,
                        //         'file_name' => $img
                        //     ]);

                        //     if ($create_file != null) {
                        //         foreach ($ids as $id) {
                        //             $c = Voyage::create([
                        //                 'user_id' => $me->id,
                        //                 'receiver_id' => $id,
                        //                 'type' => $extension,
                        //                 'image_ids' => $create_file->id
                        //             ]);
                        //         }
                        //     } else {
                        //         $response['message'] = "Something went wrong";
                        //         $response['success'] = 0;
                        //     }
                        // }
                        $users_email = User::whereIn('id', $ids)->pluck('email')->toArray();
                        $emails = implode(",", $users_email);
                        $fromEmail = Config::get('constants.from_email');
                        $fromName = Config::get('constants.from_name');
                        // dump($fromEmail);
                        // dd($fromName);
                        $message = "<p>Hi" . "\r\n";
                        
                        
                        $message .= "<p>Thank You" . "</p>\r\n";
                        $message .= "<p>We hope to see you again soon!" . "</p>\r\n";
                        $message .= "<pre>-- " . "\r\n";
                        
                 
                        \Mail::send([], [], function ($mail) use ($emails, $fromEmail, $fromName, $message) {
                            $mail->to($emails);
                            $mail->from($fromEmail, $fromName)
                                ->subject('Transfer - New file recevied From your Friend')
                                ->setBody($message, 'text/html'); // for HTML rich messages
                        });

                        $response['message'] = "Create all done";
                        $response['success'] = 1;
                    }
                } else {
                    $response['message'] = "User Not Found";
                    $response['success'] = 0;
                }
                // dd('stop here');
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
     * @param  \App\Models\Voyage  $voyage
     * @return \Illuminate\Http\Response
     */
    public function show(Voyage $voyage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Voyage  $voyage
     * @return \Illuminate\Http\Response
     */
    public function edit(Voyage $voyage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Voyage  $voyage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Voyage $voyage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Voyage  $voyage
     * @return \Illuminate\Http\Response
     */
    public function destroy(Voyage $voyage)
    {
        //
    }
}
