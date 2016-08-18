<?php
/**
 * Created by PhpStorm.
 * User: kyshniryk2
 * Date: 18.01.16
 * Time: 23:41
 */

namespace app\components;


use app\models\City;
use app\models\NetCity;

class Tools
{
    //Отримуємо текст всередині двох підрядків ($start__***__$end)
    public static function get_string_between($string, $start, $end)
    {
        $string = " ".$string;
        $ini = strpos($string,$start);
        if ($ini == 0) return "";
        $ini += strlen($start);
        $len = strpos($string,$end,$ini) - $ini;
        return substr($string,$ini,$len);
    }

    public static function getTimeZoneByCityId($cityId)
    {
        $city = NetCity::find()->where('id = :cid', [':cid' =>$cityId])->one();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://ws.geonames.org/timezone?lat=".$city->latitude."&lng=".$city->longitude."&style=full&username=kyshniryk");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        $result = intval(Tools::get_string_between($result,"<gmtOffset>","</gmtOffset>"));

        return $result;
    }

    public static function getDay($namber, $short = false)
    {
        if($short)
            switch ($namber) {
                case 1: return "ПН";
                case 2: return "ВТ";
                case 3: return "СР";
                case 4: return "ЧТ";
                case 5: return "ПТ";
                case 6: return "СБ";
                case 7: return "ВС";
            }
        else
            switch ($namber) {
                case 1: return "Понедельник";
                case 2: return "Вторник";
                case 3: return "Среда";
                case 4: return "Четверг";
                case 5: return "Пятница";
                case 6: return "Суббота";
                case 7: return "Воскресение";
            }
    }
}