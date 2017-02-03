<?php

namespace Gerfaut\Yelp\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class Coordinates
 * @package Gerfaut\Yelp\Model
 */
class Coordinates
{
    /**
     * @var double
     * @JMS\Type("double")
     */
    private $latitude;

    /**
     * @var double
     * @JMS\Type("double")
     */
    private $longitude;

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     * @return Coordinates
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     * @return Coordinates
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }
}
