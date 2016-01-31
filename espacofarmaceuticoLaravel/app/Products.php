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

    public static function getHomeCategories()
    {
        return self::where('productsCategoriesId', 10)->orWhere('productsCategoriesId', 11)->orderByRaw('RAND()')->limit(8)->get();
    }
}