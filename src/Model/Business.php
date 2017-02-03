<?php


namespace Gerfaut\Yelp\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class Business
 * @package Gerfaut\Yelp\Model
 */
class Business
{
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $id;

    /**
     * @var int
     * @JMS\Type("int")
     * @JMS\SerializedName("review_count")
     */
    private $reviewCount;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $price;

    /**
     * @var double
     * @JMS\Type("double")
     */
    private $rating;

    /**
     * @var Category[]
     * @JMS\Type("array<Gerfaut\Yelp\Model\Category>")
     */
    private $categories;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $phone;

    /**
     * @var double
     * @JMS\Type("double")
     */
    private $distance;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("image_url")
     */
    private $imageUrl;

    /**
     * @var boolean
     * @JMS\Type("boolean")
     * @JMS\SerializedName("is_closed")
     */
    private $isClosed;

    /**
     * @var Coordinates
     * @JMS\Type("Gerfaut\Yelp\Model\Coordinates")
     */
    private $coordinates;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $name;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("display_phone")
     */
    private $displayPhone;

    /**
     * @var Location
     *
     * @JMS\Type("Gerfaut\Yelp\Model\Location")
     */
    private $location;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $url;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Business
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getReviewCount()
    {
        return $this->reviewCount;
    }

    /**
     * @param int $reviewCount
     * @return Business
     */
    public function setReviewCount($reviewCount)
    {
        $this->reviewCount = $reviewCount;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param string $price
     * @return Business
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return float
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param float $rating
     * @return Business
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * @return Category[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param Category[] $categories
     * @return Business
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return Business
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return float
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * @param float $distance
     * @return Business
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;

        return $this;
    }

    /**
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * @param string $imageUrl
     * @return Business
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isIsClosed()
    {
        return $this->isClosed;
    }

    /**
     * @param boolean $isClosed
     * @return Business
     */
    public function setIsClosed($isClosed)
    {
        $this->isClosed = $isClosed;

        return $this;
    }

    /**
     * @return Coordinates
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * @param Coordinates $coordinates
     * @return Business
     */
    public function setCoordinates($coordinates)
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Business
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayPhone()
    {
        return $this->displayPhone;
    }

    /**
     * @param string $displayPhone
     * @return Business
     */
    public function setDisplayPhone($displayPhone)
    {
        $this->displayPhone = $displayPhone;

        return $this;
    }

    /**
     * @return Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param Location $location
     * @return Business
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return Business
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }
}
