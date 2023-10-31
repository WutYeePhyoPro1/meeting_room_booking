<?php
namespace App\Customize;

use App\Models\Branch;


class Commonfunction
{
    static function getBranch()
    {
        view()->share('branches',Branch::all());
    }
}
