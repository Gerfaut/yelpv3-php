<?php

namespace Gerfaut\Yelp\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class AutocompleteResponse
 * @package Gerfaut\Yelp\Model
 */
class AutocompleteResponse
{
    /**
     * @var Category[]
     *
     * @JMS\Type("array<Gerfaut\Yelp\Model\Category>")
     */
    private $categories;

    /**
     * @var Business[]
     *
     * @JMS\Type("array<Gerfaut\Yelp\Model\Business>")
     */
    private $businesses;

    /**
     * @var Term[]
     *
     * @JMS\Type("array<Gerfaut\Yelp\Model\Term>")
     */
    private $terms;

    /**
     * @return Category[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param Category[] $categories
     * @return AutocompleteResponse
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;

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
     * @return AutocompleteResponse
     */
    public function setBusinesses($businesses)
    {
        $this->businesses = $businesses;

        return $this;
    }

    /**
     * @return Term[]
     */
    public function getTerms()
    {
        return $this->terms;
    }

    /**
     * @param Term[] $terms
     * @return AutocompleteResponse
     */
    public function setTerms($terms)
    {
        $this->terms = $terms;

        return $this;
    }
}
