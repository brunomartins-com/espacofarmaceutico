<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class DigitalCatalogs extends Model {

    protected $table = 'digitalCatalogs';

    protected $primaryKey = 'digitalCatalogsId';

    public $timestamps = false;
}