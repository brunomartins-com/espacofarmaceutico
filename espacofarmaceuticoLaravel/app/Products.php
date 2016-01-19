<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Products extends Model {

    protected $table = 'products';

    protected $primaryKey = 'productsId';

    public $timestamps = false;

    public function category()
    {
        return $this->hasOne('App\ProductsCategories', 'productsCategoriesId', 'productsCategoriesId');
    }
}