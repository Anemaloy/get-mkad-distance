<?php

namespace Anemaloy\GetMKADDistance;

class YandexPoint
{
    /**
     * @var
     */
    public $x;
    /**
     * @var
     */
    public $y;

    /**
     * YandexPoint constructor.
     * @param null $point
     * @param null $x
     * @param null $y
     */
    public function __construct($point = null, $x = null, $y = null)
    {
        if (!empty($point))
        {
            $coordinate = explode(",", $point);
            $this->x = (double)$coordinate[0];
            $this->y = (double)$coordinate[1];
        }

        if (!empty($x))
        {
            $this->x = $x;
        }

        if (!empty($y))
        {
            $this->y = $y;
        }
    }

    /**
     * @param $point
     * @return YandexPoint
     */
    public function ConvertToGPSPoint($point)
    {
        $result = new YandexPoint();

        $integerX = (int)$point->x;
        $result->x = $integerX + ($point->x - $integerX) * 0.6;

        $integerY = (int)$point->y;
        $result->y = $integerY + ($point->y - $integerY) * 0.6;

        return $result;
    }

    /**
     * @param $point
     * @return YandexPoint
     */
    public function ConvertGPSToYandexPoint($point)
    {
        $result = new YandexPoint();

        $integerX = (int)$point->x;
        $result->x = $integerX + ($point->x - $integerX) / 0.6;

        $integerY = (int)$point->y;
        $result->y = $integerY + ($point->y - $integerY) / 0.6;

        return $result;
    }

    /**
     * @return string
     */
    public function ToString()
    {
        return "$this->x, $this->y";
    }

    /**
     * @return mixed
     */
    public function Latitude()
    {
        return $this->y;
    }

    /**
     * @return mixed
     */
    public function Lat()
    {
        return $this->Latitude();
    }

    /**
     * @return mixed
     */
    public function Longitude()
    {
        return $this->x;
    }

    /**
     * @return mixed
     */
    public function Long()
    {
        return $this->Longitude();
    }
}
