<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Concept extends Model {

    protected $table = 'concept';

    protected $primaryKey = 'conceptId';

    public $timestamps = false;
}