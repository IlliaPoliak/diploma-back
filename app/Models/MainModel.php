<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MainModel extends Model
{
    protected $table = "main_models";

    protected $fillable = [
        'name',
        'email'
    ];


}
