<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table ='com_countries';
    protected $fillable =
    [
        
        'country_name',
        'sortname',
        'phonecode',
        'is_deleted',
    ];
}
