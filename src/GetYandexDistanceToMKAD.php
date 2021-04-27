<?php

namespace Anemaloy\GetMKADDistance;

use Anemaloy\GetMKADDistance\YandexPoligon;
use Anemaloy\GetMKADDistance\YandexPoint;
use Illuminate\Contracts\Config\Repository;

class GetYandexDistanceToMKAD
{
    /**
     * @var
     */
    private $key;

    public function __construct(Repository $config)
    {
        $this->key = config('mkad_distance_config.drivers')[config('mkad_distance_config.driver')]['key'];
    }

    /**
     * @param $Address
     * @return false|string
     */
    public function SearchObjectByAddress($Address)
    {
        $url = "http://geocode-maps.yandex.ru/1.x/?apikey={$this->key}&geocode=" . urlencode($Address) . "&results=1";
        $result = file_get_contents($url);
        return $result;
    }

    /**
     * @param $Latitude
     * @param $Longitude
     * @return false|string
     */
    public function SearchObject($Latitude, $Longitude)
    {
        $url = "http://geocode-maps.yandex.ru/1.x/?geocode=$Latitude,$Longitude&results=1";
        $result = file_get_contents($url);
        return $result;
    }

    /**
     * @param $ResultSearchObject
     * @param $zPosition
     * @param $Width
     * @param $Height
     * @return string
     */
    public function GetUrlMapImage($ResultSearchObject, $zPosition, $Width, $Height )
    {
        $point = $this->GetPoint($ResultSearchObject);
        return "http://static-maps.yandex.ru/1.x/?ll=$point&size=$Width,$Height&z=$zPosition&l=map&pt=$point,pm2lbm&lang=ru-RU";
    }

    /**
     * @param $ResultSearchObject
     * @return string|string[]
     */
    public function GetPoint($ResultSearchObject)
    {
        $point = "";
        $xml = simplexml_load_string($ResultSearchObject);

        $point = $xml->GeoObjectCollection->featureMember->GeoObject->Point->pos;
        $point = str_replace(" ", ",", $point);

        return $point;
    }

    /**
     * @param $ResultSearchObject
     * @return YandexPoint
     */
    public function GetPointObject($ResultSearchObject)
    {
        $result = new YandexPoint($this->GetPoint($ResultSearchObject));
        return $result;
    }

    /**
     * @param $a
     * @param $b
     * @return float|int
     */
    public function GetDistance($a, $b)
    {
        $R = 6371;
        $dLat = $this->toRad($b->Lat() - $a->Lat());
        $dLon = $this->toRad($b->Long() - $a->Long());
        $lat1 = $this->toRad($a->Lat());
        $lat2 = $this->toRad($b->Lat());

        $a = sin($dLat / 2) * sin($dLat / 2) + sin($dLon / 2) * sin($dLon / 2) * cos($lat1) * cos($lat2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $d = $R * $c;

        return $d;
    }

    /**
     * @param $address
     * @return array
     */
    public function CheckMkad($address)
    {
        $polyhon = YandexPoligon::GetMkadPolygon();
        $xml = $this->SearchObjectByAddress($address);
        $coordinates = $this->GetPoint($xml);
        $point = new YandexPoint($coordinates);
        $is_mkad = $polyhon->IsInPolygon($point);
        $close_point = $polyhon->GetClosestPoint($point);
        $distance = $this->GetDistance($close_point, $point);

        $result = array(
            'point' => $point,
            'closest_point' => $close_point,
            'is_mkad' => $is_mkad,
            'distance' => $distance
        );

        return $result;
    }

    protected function toRad($v)
    {
        return $v * pi() / 180;
    }

}
