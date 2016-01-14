<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Pages extends Model {

    protected $table = 'pages';

    protected $primaryKey = 'pagesId';

    public $timestamps = false;

}