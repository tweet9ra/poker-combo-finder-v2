<?php


namespace App;


use App\Card\CardCollection;

class Combination
{
    public function __construct(string $name, float $weight, CardCollection $usedCards)
    {
        $this->name = $name;
        $this->weight = $weight;
        $this->usedCards = $usedCards;
    }

    /**
     * Название комбинации
     * @var string $name
     */
    protected $name;

    /**
     * Вес комбинации, больше - лучше
     * @var float|int $weight
     */
    protected $weight;

    /**
     * Карты, использованные для создания комбинации
     * @var CardCollection $usedCards
     */
    protected $usedCards;

    public function getName()
    {
        return $this->name;
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function getUsedCards()
    {
        return $this->usedCards;
    }
}