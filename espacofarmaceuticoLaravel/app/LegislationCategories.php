<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class LegislationCategories extends Model {

    protected $table = 'legislationCategories';

    protected $primaryKey = 'legislationCategoriesId';

    public $timestamps = false;

    public function legislations()
    {
        return $this->hasMany('App\Legislation', 'legislationCategoriesId', 'legislationCategoriesId')->orderBy('sortorder', 'ASC');
    }
}