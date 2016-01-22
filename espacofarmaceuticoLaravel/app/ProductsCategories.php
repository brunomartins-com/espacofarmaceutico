<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class ProductsCategories extends Model {

    protected $table = 'productsCategories';

    protected $primaryKey = 'productsCategoriesId';

    public $timestamps = false;

    public function products()
    {
        return $this->hasMany('App\Products', 'productsCategoriesId', 'productsCategoriesId')->orderBy('name', 'ASC');
    }

    public static function deleteProductsByCategory($productsCategoriesId)
    {
        $folder       = "assets/images/_upload/products/";
        $folderBull   = "assets/bulas/";

        $products = Products::where('productsCategoriesId', $productsCategoriesId)->get();
        foreach($products as $product):
            if ($product->bull != "") {
                if (File::exists($folderBull . $product->bull)) {
                    File::delete($folderBull . $product->bull);
                }
            }
            if ($product->image != "") {
                if (File::exists($folder . $product->image)) {
                    File::delete($folder . $product->image);
                }
            }
        endforeach;

        Products::where('productsCategoriesId', '=', $productsCategoriesId)->delete();

        return self::where('usersId', $userId)->delete();
    }
}