<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Events extends Model {

    protected $table = 'events';

    protected $primaryKey = 'eventsId';

    public $timestamps = false;

    public function typeName($type){
        switch($type){
            case 0:
                return "Nacional";
                break;
            case 1:
                return "Internacional";
                break;
        }
    }
}