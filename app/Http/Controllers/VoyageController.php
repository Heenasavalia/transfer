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

class VoyageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function CreateTransferData(Request $request){
        dump('wel-come to fun');
        $response = Helpers::getResponce();
        try{
            $validator = Validator::make($request->all(), [
            //    'emails' => 'required',
                
            ]);
            if ($validator->fails()) {
                // dd($validator->fails());
                $response['message'] = $validator->errors()->first();
            } else {
                $emails = [];
                $me = JWTAuth::parseToken()->authenticate();
                
                if($me != null){
                    dd($me->id);
                }else{
                    $response['message'] = "User Not Found";
                    $response['success'] = 0;
                }
            }
        }catch(Exception $e){
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
