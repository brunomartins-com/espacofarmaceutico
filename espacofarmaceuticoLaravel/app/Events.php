<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Events extends Model {

    protected $table = 'events';

    protected $primaryKey = 'eventsId';

    public $timestamps = false;
}