<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Legislation extends Model {

    protected $table = 'legislation';

    protected $primaryKey = 'legislationId';

    public $timestamps = false;

    public function category()
    {
        return $this->hasOne('App\LegislationCategories', 'legislationCategoriesId', 'legislationCategoriesId');
    }
}