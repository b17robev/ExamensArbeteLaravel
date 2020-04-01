<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    protected $fillable = [
        'airport_id',
        'name', 'city', 'country', 'iata', 'icao',
        'latitude', 'longitude', 'altitude', 'timezone', 'dst',
        'tz_database_time_zone', 'type', 'source', 'location',
    ];

    protected $primaryKey = 'airport_id';
}
