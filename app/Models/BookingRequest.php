<?php

namespace App\Models;

use App\Models\User;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'request_reason',
        'request_status',
        'resend_status',
        'read',
        'request_user',
        'approve_user',
        'from',
        'total_duration'
    ];

    public function booking() :BelongsTo
    {
        return $this->belongsTo(Booking::class,'booking_id')->withDefault();
    }

    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class,'request_user')->withDefault();
    }

    public function approve() :BelongsTo
    {
        return $this->belongsTo(User::class ,'approve_user')->withDefault();
    }
}
