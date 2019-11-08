<?php


namespace App;


use App\Card\Card;
use App\Card\CardCollection;

abstract class AbstractCombinationFinder
{
    /**
     * Реализация должна обеспечивать поиск комбинации
     * @param CardCollection $cards
     * @return Combination
     */
    abstract public function find(CardCollection $cards) : Combination;

    /**
     * Ищет флэш, возвращает все карты одной масти, если их >=5
     * @param CardCollection $cards
     * @return CardCollection|null
     */
    protected function getFlush(CardCollection $cards)
    {
        // Создаем массив формата [suit] => [card1, card2, ...] для подсчета карт одной масти
        $valuesBySuit = $cards->filterBySuit();

        foreach ($valuesBySuit as $suit => $filteredCards) {
            if (count($filteredCards) >= 5) {
                return new CardCollection($filteredCards);
            }
        }
    }

    /**
     * Получить самый старший стрит
     * @param CardCollection $immutableCards
     * @return CardCollection
     */
    protected function getFattestStraight(CardCollection $immutableCards)
    {
        $cards = clone $immutableCards;

        // Если первая карта - туз, добавляем его и в конец,
        // чтобы обнаружить возможный младший стрит
        if ($cards[0]->getValue() === 'A') {
            $cards[] = $cards[0];
        }

        $straight = [];

        foreach ($cards as $key => $card) {
            if ($straight
                && $card->getWeight() !== ($cards[$key - 1]->getWeight() - 1)
                && !($card->getValue() == 'A' && $cards[$key - 1]->getValue() == 2)
            ) {
                $straight = [];
            }

            $straight[] = $card;
            if (count($straight) == 5) {
                return new CardCollection($straight);
            }
        }
    }

    /**
     * Поиск 2-4 одинаковых карт
     * @param CardCollection $cards
     * @return array
     */
    protected function getDuplicatedCards(CardCollection $cards) : array
    {
        $result = [
            4 => [],
            3 => [],
            2 => []
        ];

        $cardsByValue = $cards->filterByValue();

        foreach ($cardsByValue as $value => $filteredCards) {
            $count = count($filteredCards);
            if ($count > 1 && $count < 5) {
                $result[$count][] = $filteredCards;
            }
        }

        return $result;
    }

    protected function getFattestDuplicateKey(array $duplicates)
    {
        return $this->getDuplicateKey($duplicates);
    }

    protected function getLowestDuplicateKey(array $duplicates)
    {
        return $this->getDuplicateKey($duplicates, false);
    }

    private function getDuplicateKey(array $duplicates, $mode = true)
    {
        $duplicateKey = 0;
        $duplicateWeight = 1337;

        foreach ($duplicates as $key => $duplicate) {
            $weight = $duplicate[0]->getWeight();
            if ($mode ? $weight > $duplicateWeight : $weight < $duplicateWeight) {
                $duplicateWeight = $weight;
                $duplicateKey = $key;
            }
        }

        return $duplicateKey;
    }
}