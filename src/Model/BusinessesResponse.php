<?php

namespace Gerfaut\Yelp\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class BusinessesResponse
 * @package Gerfaut\Yelp\Model
 */
class BusinessesResponse
{
    /**
     * @var Region
     * @JMS\Type("Gerfaut\Yelp\Model\Region")
     */
    private $region;

    /**
     * @var int
     *
     * @JMS\Type("int")
     */
    private $total;

    /**
     * @var Business[]
     *
     * @JMS\Type("array<Gerfaut\Yelp\Model\Business>")
     */
    private $businesses;

    /**
     * @return Region
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param Region $region
     * @return BusinessesResponse
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param int $total
     * @return BusinessesResponse
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return Business[]
     */
    public function getBusinesses()
    {
        return $this->businesses;
    }

    /**
     * @param Business[] $businesses
     * @return BusinessesResponse
     */
    public function setBusinesses($businesses)
    {
        $this->businesses = $businesses;

        return $this;
    }
}
