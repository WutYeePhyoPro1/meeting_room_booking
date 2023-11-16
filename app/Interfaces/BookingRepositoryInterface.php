<?php

namespace App\Interfaces;

interface BookingRepositoryInterface{
    public function check_avaliable($date,$room_id,$id = null);
    public function min_gap(array $data,$start,$end);
}
