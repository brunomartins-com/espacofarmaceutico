<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CommonQuestions extends Model {

    protected $table = 'commonQuestions';

    protected $primaryKey = 'commonQuestionsId';

    public $timestamps = false;
}