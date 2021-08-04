<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Helpers;

class TransferInfo extends Model
{
    use HasFactory;

    protected $table = "transfer_info";
    protected $fillable = [
        'voyage_id','type','file_name','is_delete','created_at','updated_at','ref_id'
    ];

    public function getFileNameAttribute($value)
    {
        // dd($value);
        if($value){
            return Helpers::manageUploadFileLink('file_data',$value);
        }else{
            return asset('images/default.png');
        }
    }
}
