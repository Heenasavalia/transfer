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
                // 'type' => 'required|in:send,recevied'
                // 'type' => 'required'
            ]);
            if ($validator->fails()) {
                $response['message'] = $validator->errors()->first();
            } else {
                $me = JWTAuth::parseToken()->authenticate();
                $type = (isset($data['type']) && $data['type'] != null) ? $data['type'] : null;
                $limit = (isset($data['limit']) && $data['limit'] != null) ? $data['limit'] : 50;
                $offset = (isset($data['offset']) && $data['offset'] != null) ? $data['offset'] : 0;
                $keyword = (isset($data['keyword']) && $data['keyword'] != null) ? $data['keyword'] : null;
                if ($type == 'send') {
                    // $send_data = Voyage::with('user')->where('user_id', $me->id)->where('is_delete', 0)->get();
                    $send_data = Voyage::select('voyages.id', 'voyages.description', 'voyages.receiver_id', 'voyages.created_at', 'users.first_name', 'users.last_name')
                        ->join('users', 'voyages.receiver_id', 'users.id')
                        // ->join('transfer_info','voyages.file_id','transfer_info.id')
                        ->where('voyages.user_id', $me->id)->orderBy('voyages.created_at', 'DESC')->get();

                    // if($keyword != null){
                    //     $send_data = $send_data->where(function($query) use($keyword) {
                    //         $query->where('users.first_name', 'LIKE', '%' . $keyword . '%')->orWhere('users.last_name', 'LIKE', '%' . $keyword . '%');
                    //     });
                    // }

                    // $send_data = $send_data->limit($limit)->offset($offset)->get();

                    // ->limit($limit)->offset($offset)->get();
                    // dump($send_data);
                    if ($send_data != null) {
                        $response['success'] = 1;
                        $response['message'] = "Data retrive success";
                        $response['data'] = $send_data;
                    } else {
                        $response['success'] = -1;
                        $response['message'] = "Data not found";
                        $response['data'] = null;
                    }
                } elseif ($type == 'recevied') {
                    // dd("rhwgfsh");
                } else {
                    // $all_data = Voyage::with(['user' => function($q){$q->select('id', 'first_name', 'last_name', 'email');}])->select('voyages.id', 'voyages.description', 'voyages.receiver_id', 'voyages.is_read','voyages.created_at', 'users.first_name', 'users.last_name','users.email',)
                    //     ->join('users', 'voyages.receiver_id', 'users.id')
                    //     // ->join('transfer_info','voyages.file_id','transfer_info.id')
                    //     ->where('voyages.user_id', $me->id)->orWhere('voyages.receiver_id', $me->id)->get();
                        $all_data = Voyage::with(['transfer',
                            'sender' => function ($query) {
                                $query->select('id', 'first_name', 'last_name', 'email');
                            },
                            'receiver' => function ($query) {
                                $query->select('id', 'first_name', 'last_name', 'email');
                            },
                        ])
                        // ->select('transfer_info.id','transfer_info.file_name')
                        // ->join('transfer_info','voyages.file_id','transfer_info.id')
                        // ->select();
                        ->where(function ($query) use ($me) {
                            $query->where('user_id', $me->id)
                                ->orWhere('receiver_id', $me->id);
                        })
                        ->get();
                    if ($all_data != null) {
                        $response['success'] = 1;
                        $response['message'] = "Data retrive success";
                        $response['data'] = $all_data;
                    } else {
                        $response['success'] = -1;
                        $response['message'] = "Data not found";
                        $response['data'] = null;
                    }
                }
            }
            // dd("stop here");
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }
        return response()->json($response);
    }


    public function CreateTransferData(Request $request)
    {
      
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

                if ($me != null) {
                    $receiver_ids = (isset($data['receiver_id']) && $data['receiver_id'] != null) ? $data['receiver_id'] : null;
                    $ids = ($receiver_ids != null) ? explode(",", $receiver_ids) : [];

                    $contents = (isset($data['contents']) && $data['contents'] != null) ? $data['contents'] : null;
                
                    if (count($ids) > 5) {
                        $response['success'] = 0;
                        $response['data'] = null;
                        $response['message'] = "You can only share to 5 Member";
                    } else {
                        
                        $number = mt_rand(10000000, 99999999); // better than rand()
                        $find = TransferInfo::where('ref_id',$number)->get();
                        if($find != null){
                            $response['success'] = 0;
                            $response['data'] = null;
                            $response['message'] = "Something went wrong!";
                        }

                        if ($contents != null) {

                            foreach ($contents as $c) {

                                $extension = $c['file']->getClientOriginalExtension();
                                $img = Helpers::upload_image($c['file'], config('constants.file_data'));
                            
                                $create_file = TransferInfo::create([
                                    'type' => $extension,
                                    'file_name' => $img,
                                    'ref_id' => $number,
                                ]);
                                $link_data[] = $create_file->file_name;

                                if ($create_file != null) {
                                    foreach ($ids as $id) {
                                        $c = Voyage::create([
                                            'user_id' => $me->id,
                                            'receiver_email' => $id,
                                            // 'receiver_id' => $id,
                                            'type' => $extension,
                                            'file_id' => $create_file->id
                                        ]);
                                        // $email = User::where('id', $id)->first()->email;
                                        // $emails[] = $email;
                                        // dump($emails);
                                    }
                                } else {
                                    $response['message'] = "Something went wrong";
                                    $response['success'] = 0;
                                }
                            }

                            // dump('stop here');
                            $fromEmail = Config::get('constants.from_email');
                            $fromName = Config::get('constants.from_name');
                            $message = "<p>Hi" . "\r\n";
                            $message .= "<p>Thank You" . "</p>\r\n";
                            $message .= "<p>We hope to see you again soon!" . "</p>\r\n";
                            $message .= "<pre>-- " . "\r\n";

                            \Mail::send('send_transferlink', function ($mail) use ($emails, $fromEmail, $fromName, $message) {
                                $mail->to($emails);
                                $mail->from($fromEmail, $fromName)
                                    ->subject('Transfer - New file recevied From your Friend')
                                    ->setBody($message, 'text/html'); // for HTML rich messages
                            }); 
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
                    $response['success'] = -1;
                }
                // dd('stop here');
            }
        } catch (Exception $e) {
            dd($e);
            $response['message'] = $e->getMessage();
        }
        return response()->json($response);
    }



    public function CreateTransferData_old(Request $request)
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

                                // dump($link_data);

                                if ($create_file != null) {
                                    foreach ($ids as $id) {
                                        $c = Voyage::create([
                                            'user_id' => $me->id,
                                            'receiver_id' => $id,
                                            'type' => $extension,
                                            'file_id' => $create_file->id
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

                            // dump('stop here');
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
                            // $maildata = [
                            //     // 'name' => $user->first_name . ' ' . $user->last_name,
                            //     'link' => "http://3.17.228.42/file_data/" . $link_data
                            // ];

                            // dd($maildata);
                            //Helpers::sendMail('send_transferlink', $maildata, $emails, "TRANSFER - New file recevied From your Friend");

                            $response['message'] = "Create all done";
                            $response['success'] = 1;
                        }
                    }
                } else {
                    $response['message'] = "User Not Found";
                    $response['success'] = -1;
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
