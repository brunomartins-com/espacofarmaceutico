<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailsContact extends Model {

    protected $table = 'emailsContact';

    protected $primaryKey = 'emailsContactId';

    public $timestamps = false;
}