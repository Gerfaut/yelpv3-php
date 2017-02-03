<?php

namespace Gerfaut\Yelp\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class Location
 * @package Gerfaut\Yelp\Model
 */
class Location
{
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("address1")
     */
    private $addressFirstLine;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("address2")
     */
    private $addressSecondLine;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("address3")
     */
    private $addressThirdLine;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $state;

    /**
     * @var string
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("display_address")
     */
    private $displayAddress;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $country;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $city;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("zip_code")
     */
    private $zipCode;

    /**
     * @return string
     */
    public function getAddressFirstLine()
    {
        return $this->addressFirstLine;
    }

    /**
     * @param string $addressFirstLine
     * @return Location
     */
    public function setAddressFirstLine($addressFirstLine)
    {
        $this->addressFirstLine = $addressFirstLine;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddressSecondLine()
    {
        return $this->addressSecondLine;
    }

    /**
     * @param string $addressSecondLine
     * @return Location
     */
    public function setAddressSecondLine($addressSecondLine)
    {
        $this->addressSecondLine = $addressSecondLine;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddressThirdLine()
    {
        return $this->addressThirdLine;
    }

    /**
     * @param string $addressThirdLine
     * @return Location
     */
    public function setAddressThirdLine($addressThirdLine)
    {
        $this->addressThirdLine = $addressThirdLine;

        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     * @return Location
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayAddress()
    {
        return $this->displayAddress;
    }

    /**
     * @param string $displayAddress
     * @return Location
     */
    public function setDisplayAddress($displayAddress)
    {
        $this->displayAddress = $displayAddress;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return Location
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return Location
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode
     * @return Location
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }
}
