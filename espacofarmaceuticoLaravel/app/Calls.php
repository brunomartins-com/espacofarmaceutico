<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Calls extends Model {

    protected $table = 'calls';

    protected $primaryKey = 'callsId';

    public $timestamps = false;
}