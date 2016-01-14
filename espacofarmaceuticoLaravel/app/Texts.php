<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Texts extends Model {

    protected $table = 'texts';

    protected $primaryKey = 'textsId';

    public $timestamps = false;
}