<?php

namespace App\Rules;

use App\Models\MeetingRoom;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RoomNameDublicate implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    private $branch_id;
    private $id;

    public function __construct($branch_id,$id = '') {
        $this->branch_id = $branch_id;
        $this->id        = $id;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if($this->id){
            $data = MeetingRoom::where(['room_name'=>$value,'branch_id'=>$this->branch_id])->where('id','!=',$this->id)->first();
        }else{
            $data = MeetingRoom::where(['room_name'=>$value,'branch_id'=>$this->branch_id])->first();
        }
        if($data){
            $fail('Room Name Dublicate!!Please Change');
        }
    }
}
