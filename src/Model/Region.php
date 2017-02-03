<?php

namespace Gerfaut\Yelp\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class Region
 * @package Gerfaut\Yelp\Model
 */
class Region
{
    /**
     * @var Center
     * @JMS\Type("Gerfaut\Yelp\Model\Center")
     */
    private $center;

    /**
     * @return Center
     */
    public function getCenter()
    {
        return $this->center;
    }

    /**
     * @param Center $center
     * @return Region
     */
    public function setCenter($center)
    {
        $this->center = $center;

        return $this;
    }
}
