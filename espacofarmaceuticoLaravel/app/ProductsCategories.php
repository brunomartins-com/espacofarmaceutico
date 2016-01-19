<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductsCategories extends Model {

    protected $table = 'productsCategories';

    protected $primaryKey = 'productsCategoriesId';

    public $timestamps = false;

    public function products()
    {
        return $this->hasMany('App\Products', 'productsCategoriesId', 'productsCategoriesId')->orderBy('name', 'ASC');
    }
}