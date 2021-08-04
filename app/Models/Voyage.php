<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Voyage extends Model
{
    use HasFactory;

    protected $table = "voyages";
    protected $fillable = [
        'user_id', 'receiver_id', 'type', 'image_ids', 'video_ids', 'contact_ids', 'audio_ids',
        'document_id', 'location_id', 'is_read', 'is_delete', 'auto_delete', 'is_download', 'file_id', 'expired_at', 'description',
        'receiver_email'
    ];

    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d-M-Y H:i A');
    }
    
    public function transfer()
    {
        return $this->belongsTo('App\Models\TransferInfo');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function receiver()
    {
        return $this->belongsTo('App\Models\User', 'receiver_id', 'id');
    }
    public function sender()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
