<?php

//

namespace App\Http;

use App\Models\User;

use JWTAuth;

class Helpers
{

    public static function getResponce()
    {
        $response = [
            "success" => 0,
            "message" => "",
        ];
        return $response;
    }

    public static function upload_image ($image, $path){

    }

    
}
