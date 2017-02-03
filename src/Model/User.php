<?php

namespace Gerfaut\Yelp\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class User
 * @package Gerfaut\Yelp\Model
 */
class User
{
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("image_url")
     */
    private $imageUrl;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $name;

    /**
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * @param string $imageUrl
     * @return User
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;

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
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
