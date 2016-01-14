<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsAndReleases extends Model {

    protected $table = 'newsAndReleases';

    protected $primaryKey = 'newsAndReleasesId';

    public $timestamps = false;

    public static function tagsList($tags, $page) {
        $tag_s = explode(",", $tags);
        $qtd = count($tag_s);
        $sufix = ", ";

        $finalTag = "";
        for($i=0; $i<$qtd; $i++){
            $finalTag .= '<a href="/'.$page.'/busca/?palavra='.$tag_s[$i].'" title="'.$tag_s[$i].'">'.$tag_s[$i].'</a>';
            if($i < ($qtd-1)){
                $finalTag .= $sufix;
            }
        }

        return $finalTag;
    }
}