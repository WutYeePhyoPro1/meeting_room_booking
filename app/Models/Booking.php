<?php

namespace App\Models;

use App\Models\User;
use App\Models\Reason;
use App\Models\MeetingRoom;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'room_id',
        'date',
        'start_time',
        'end_time',
        'duration',
        'title',
        'reason_id',
        'user_id',
        'remark',
        'extend_status',
        'extended_duration',
        'extended_time',
        'status',
        'finished_time',
        'noti'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id')->withDefault();
    }

    public function room()
    {
        return $this->belongsTo(MeetingRoom::class,'room_id')->withDefault();
    }

    public function reason()
    {
        return $this->belongsTo(Reason::class,'reason_id')->withDefault();
    }
}
