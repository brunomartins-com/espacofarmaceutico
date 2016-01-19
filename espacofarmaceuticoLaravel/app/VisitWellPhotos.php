<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class VisitWellPhotos extends Model {

    protected $table = 'visitWellPhotos';

    protected $primaryKey = 'visitWellPhotosId';

    public $timestamps = false;

    public static function portugueseMonthName($numberOfMonth)
    {
        switch($numberOfMonth){
            case "01":
                return "Janeiro";
                break;
            case "02":
                return "Fevereiro";
                break;
            case "03":
                return "Março";
                break;
            case "04":
                return "Abril";
                break;
            case "05":
                return "Maio";
                break;
            case "06":
                return "Junho";
                break;
            case "07":
                return "Julho";
                break;
            case "08":
                return "Agosto";
                break;
            case "09":
                return "Setembro";
                break;
            case "10":
                return "Outubro";
                break;
            case "11":
                return "Novembro";
                break;
            case "12":
                return "Dezembro";
                break;
        }
    }
}