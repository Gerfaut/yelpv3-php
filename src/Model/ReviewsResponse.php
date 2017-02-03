<?php

namespace Gerfaut\Yelp\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class ReviewsResponse
 * @package Gerfaut\Yelp\Model
 */
class ReviewsResponse
{
    /**
     * @var int
     * @JMS\Type("int")
     */
    private $total;

    /**
     * @var Review[]
     *
     * @JMS\Type("array<Gerfaut\Yelp\Model\Review>")
     */
    private $reviews;

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param int $total
     * @return ReviewsResponse
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return Review[]
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * @param Review[] $reviews
     * @return ReviewsResponse
     */
    public function setReviews($reviews)
    {
        $this->reviews = $reviews;

        return $this;
    }
}
