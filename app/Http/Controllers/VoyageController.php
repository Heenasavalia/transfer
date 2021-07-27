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
    public function displayTransferData(Request $request)
    {
        $response = Helpers::getResponce();
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'type' => 'required|in:send,recevied'
            ]);
            if ($validator->fails()) {
                $response['message'] = $validator->errors()->first();
            } else {
                $me = JWTAuth::parseToken()->authenticate();
                dump($me->id);
                if ($data['type'] == 'send') {
                    $send_data = Voyage::where('user_id', $me->id)->where('is_delete', 0)->get();
                    dump($send_data);
                } else {
                }
            }
            dd("stop here");
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }
        return response()->json($response);
    }




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
                $link_data = [];
                $me = JWTAuth::parseToken()->authenticate();
                // dd($me->id);

                if ($me != null) {
                    $receiver_ids = (isset($data['receiver_id']) && $data['receiver_id'] != null) ? $data['receiver_id'] : null;
                    $ids = ($receiver_ids != null) ? explode(",", $receiver_ids) : [];
                    $contents = (isset($data['contents']) && $data['contents'] != null) ? $data['contents'] : null;
                    // dump($contents);
                    if (count($ids) > 5) {
                        $response['message'] = "You can only share to 5 Member";
                        $response['success'] = 0;
                        $response['data'] = null;
                    } else {
                        //dd("yes come come");
                        if ($contents != null) {

                            foreach ($contents as $c) {

                                $extension = $c['file']->getClientOriginalExtension();
                                $img = Helpers::upload_image($c['file'], config('constants.file_data'));
                                $create_file = TransferInfo::create([
                                    'type' => $extension,
                                    'file_name' => $img
                                ]);
                                $link_data[] = $create_file->file_name;

                                dump($link_data);

                                if ($create_file != null) {
                                    foreach ($ids as $id) {
                                        $c = Voyage::create([
                                            'user_id' => $me->id,
                                            'receiver_id' => $id,
                                            'type' => $extension,
                                            'image_ids' => $create_file->id
                                        ]);
                                        $email = User::where('id', $id)->first()->email;
                                        $emails[] = $email;
                                        // dump($emails);
                                    }
                                } else {
                                    $response['message'] = "Something went wrong";
                                    $response['success'] = 0;
                                }
                            }

                            dump('stop here');
                            // $fromEmail = Config::get('constants.from_email');
                            // $fromName = Config::get('constants.from_name');
                            // $message = "<p>Hi" . "\r\n";
                            // $message .= "<p>Thank You" . "</p>\r\n";
                            // $message .= "<p>We hope to see you again soon!" . "</p>\r\n";
                            // $message .= "<pre>-- " . "\r\n";

                            // \Mail::send('send_transferlink', function ($mail) use ($emails, $fromEmail, $fromName, $message) {
                            //     $mail->to($emails);
                            //     $mail->from($fromEmail, $fromName)
                            //         ->subject('Transfer - New file recevied From your Friend')
                            //         ->setBody($message, 'text/html'); // for HTML rich messages
                            // });
                            // dd("dhfhg");
                            $maildata = [
                                // 'name' => $user->first_name . ' ' . $user->last_name,
                                'link' => "http://3.17.228.42/file_data/" . $link_data
                            ];

                            // dd($maildata);
                            Helpers::sendMail('send_transferlink', $maildata, $emails, "TRANSFER - New file recevied From your Friend");

                            $response['message'] = "Create all done";
                            $response['success'] = 1;
                        }
                    }
                } else {
                    $response['message'] = "User Not Found";
                    $response['success'] = 0;
                }
                // dd('stop here');
            }
        } catch (Exception $e) {
            dd($e);
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
