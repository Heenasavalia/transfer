<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voyage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','receiver_id','type','image_ids','video_ids','contact_ids','audio_ids',
        'document_id','location_id','is_read','is_delete','auto_delete'
    ];
}
