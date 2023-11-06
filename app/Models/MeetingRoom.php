<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\RoomImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MeetingRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_name',
        'branch_id',
        'seat',
        'status',
        'boss'
    ];

    public function branches()
    {
        return $this->belongsTo(Branch::class,'branch_id')->withDefault();
    }

    public function image()
    {
        return $this->hasOne(RoomImage::class,'room_id','id');
    }
}
