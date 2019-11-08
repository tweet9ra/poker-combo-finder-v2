<?php


namespace App;

use App\Card\CardCollection;

/**
 * Игрок
 * Class Player
 * @package App
 */
class Player
{
    /** @var CardCollection $cards*/
    protected $cards;

    /** @var string $id */
    protected $id;

    /**
     * Player constructor.
     * @param string $id
     * @param CardCollection $cards
     */
    public function __construct(string $id, CardCollection $cards)
    {
        $this->id = $id;
        $this->cards = $cards;
    }

    public function getCards() :? CardCollection
    {
        return clone $this->cards;
    }

    public function getId()
    {
        return $this->id;
    }
}