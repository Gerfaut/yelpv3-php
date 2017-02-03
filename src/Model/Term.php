<?php

namespace Gerfaut\Yelp\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class Term
 * @package Gerfaut\Yelp\Model
 */
class Term
{
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $text;

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return Term
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }
}
