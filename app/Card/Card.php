<?php


namespace App\Card;

use App\Config;

/**
 * Карта
 * Immutable
 * Class Card
 * @package App\Card
 */
class Card
{
    protected $suit;
    protected $value;

    public function __construct(string $suit, $value)
    {
        $this->suit = $suit;
        $this->value = $value;
    }

    public function getSuit()
    {
        return $this->suit;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getSignature()
    {
        return $this->value.$this->suit;
    }

    /**
     * @return int
     */
    public function getWeight()
    {
        return array_search($this->value, Config::get('availableCardValues'));
    }

    public function __toString()
    {
        return $this->getSignature();
    }
}