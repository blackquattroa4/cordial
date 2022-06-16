<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Person extends Model
{
    // Person model

    protected $table = 'people';

    protected $connection = 'mongodb';

    protected $fillable = [ 'name', 'birthdate', 'timezone' ];

}
