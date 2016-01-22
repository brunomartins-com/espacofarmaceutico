<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Banners extends Model {

    protected $table = 'banners';

    protected $primaryKey = 'bannersId';

    public $timestamps = false;
}