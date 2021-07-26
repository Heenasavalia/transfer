<?php

//

namespace App\Http;

use App\Models\User;
use Mail;
use Illuminate\Support\Facades\Config;
use JWTAuth;

class Helpers
{
    public static function bytesToHuman($size)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        dump($size);
        $size /= 1024;
        dd($size);
        return round($size, 2);
    }

    public static function sendMail($template_name, $data, $to, $subject)
    {
        $fromEmail = Config::get('constants.from_email');
        $fromName = Config::get('constants.from_name');
        // dd($template_name, $data, $to, $subject);
        \Mail::send($template_name, $data, function ($message) use ($fromEmail, $fromName, $subject, $to) {
            $message->from($fromEmail, $fromName);
            $message->to($to);
            $message->subject($subject);
        });
    }

    public static function getResponce()
    {
        $response = [
            "success" => 0,
            "message" => "",
        ];
        return $response;
    }

    public static function upload_image($image, $path)
    {
        // dd($image,$path);
        $fileName = time() . rand(11111, 99999) . '.' . $image->getClientOriginalExtension();
        $p = $image->move($path, $fileName);
        // dd($p);
        if ($p) {
            return $fileName;
        } else {
            return "default.png";
        }
    }
    public static function manageUploadFileLink($path, $value = null)
    {
        // dd($path,$value);
        // dd($value);
        if ($value != null) {
            $link = "http://127.0.0.1:8000/" . $path . "/" . $value;
        } else {
            $link = "http://127.0.0.1:8000/" . $path . "/";
        }
        return $link;
    }
}
