<?php


namespace App;


use App\Card\Card;
use App\Card\CardCollection;

/**
 * Class CombinationFinder
 * @package App
 */
class TexasHoldemCombinationFinder extends AbstractCombinationFinder
{
    // Названия комбинаций
    const ROYAL_FLUSH = 'Royal Flush';
    const STRAIGHT_FLUSH = 'Straight Flush';
    const FOUR_OF_A_KIND = 'Four of a Kind';
    const FULL_HOUSE = 'Full House';
    const FLUSH = 'Flush';
    const STRAIGHT = 'Straight';
    const THREE_OF_A_KIND = 'Three of a Kind';
    const TWO_PAIR = 'Two pair';
    const ONE_PAIR = 'One pair';
    const HIGH_CARD = 'High card';

    public function find(CardCollection $cards): Combination
    {
        $cards->sortByValue();
        $flush = $this->getFlush($cards);

        if ($flush) {
            // Если есть 5+ карт одной масти, то проверяем есть ли среди них стрит
            $straightFlush = $this->getFattestStraight($flush);
            if ($straightFlush) {
                if ($straightFlush[0]->getValue() === 'A') {
                    // Если старшая карта в стрите - туз, то это флеш рояль
                    return $this->flushRoyaleCombo($straightFlush);
                } else {
                    // Иначе стрит флеш
                    return $this->straightFlushCombo($straightFlush);
                }
            }
        }

        // Поиск 2-4 карт с одинаковым значением
        $duplicates = $this->getDuplicatedCards($cards);

        if ($duplicates[4]) {
            $fourCombo = new CardCollection($duplicates[4][0]);
            return $this->fourOfAKindCombo($fourCombo, $cards->diff($fourCombo)->first());
        }

        // Фулл хауз (3 + 2) можно составить из двух троек
        // Так что нужно найти самую жирную тройку
        // И также самую мощную пару с учетом, что она может быть взята из слабой тройки
        if ($duplicates[3]) {
            if (count($duplicates[3]) > 1) {
                $fattestTrippleIndex = $this->getFattestDuplicateKey($duplicates[3]);
                $fattestTripple = $duplicates[3][$fattestTrippleIndex];
            } else {
                $fattestTripple = $duplicates[3][0];
            }
        }

        if (count($duplicates[3]) > 1 || ($duplicates[3] && $duplicates[2])) {
            $pairs = $duplicates[2];
            // Если есть слабая тройка, то добавляем её в стек пар
            if (isset($fattestTrippleIndex)) {
                // Индекс может быть 1 или 0, т.к в стеке макс. 7 карт помещаются максимум 2 тройки
                $weakTripple = $duplicates[3][(int)!$fattestTrippleIndex];
                // Убираем 1 карту из тройки, чтобы она в результате была как пара
                unset($weakTripple[2]);
                $pairs[] = $weakTripple;
            }
            // Вычисляем сильнейшую пару
            $fattestPairKey = $this->getFattestDuplicateKey($pairs);

            $fattestFullHouse = [$fattestTripple, $pairs[$fattestPairKey]];
            return $this->fullHouseCombo($fattestFullHouse);
        }

        if ($flush) {
            // У нас в $flush могут быть больше 5 карт, убираем самые слабые
            while (count($flush) !== 5) {
                $flush->pop();
            }
            return $this->flushCombo($flush);
        }

        if ($straight = $this->getFattestStraight($cards)) {
            return $this->straightCombo($straight);
        }

        if (isset($fattestTripple)) {
            $trippleCollection = new CardCollection($fattestTripple);
            $otherCards = $cards->diff($trippleCollection);
            return $this->threeOfAKindCombo($trippleCollection, $otherCards->slice(0, 2));
        }

        if (count($duplicates[2]) > 1) {
            if (count($duplicates[2]) == 2) {
                $pairs = new CardCollection(array_merge($duplicates[2][0], $duplicates[2][1]));
            } else {
                // Если на столе 3 пары, то выбираем 2 самые сочные
                $lowestPairKey = $this->getLowestDuplicateKey($duplicates[2]);
                array_splice($duplicates[2], $lowestPairKey, 1);
                $pairs = new CardCollection(array_merge($duplicates[2][0], $duplicates[2][1]));
            }

            return $this->twoPairCombo($pairs, $cards->diff($pairs)[0]);
        }

        if ($duplicates[2]) {
            $duplicate = new CardCollection($duplicates[2][0]);
            return $this->onePairCombo($duplicate, $cards->diff($duplicate)->slice(0, 3));
        }

        return $this->kickerCombo($cards->slice(0, 5));
    }

    protected function flushRoyaleCombo(CardCollection $cards)
    {
        return new Combination(self::ROYAL_FLUSH, 1337, $cards);
    }

    protected function straightFlushCombo(CardCollection $cards)
    {
        $comboWeight = 900 + $cards[0]->getWeight();
        return new Combination(self::STRAIGHT_FLUSH, $comboWeight, $cards);
    }

    protected function fourOfAKindCombo(CardCollection $cards, Card $kicker)
    {
        $comboWeight = 800 + $cards[0]->getWeight() + $kicker->getWeight()/100;
        return new Combination(self::FOUR_OF_A_KIND, $comboWeight, $cards->push($kicker));
    }

    protected function fullHouseCombo(array $cards)
    {
        [$tripple, $double] = $cards;

        $comboWeight = 700 + $tripple[0]->getWeight() + ($double[0]->getWeight()/100);

        $usedCardsCollection = new CardCollection(array_merge($tripple, $double));

        return new Combination(self::FULL_HOUSE, $comboWeight, $usedCardsCollection);
    }

    protected function flushCombo(CardCollection $cards)
    {
        $comboWeight = 600;

        foreach ($cards as $key => $card) {
            $comboWeight += $key
                ? $card->getWeight() / pow( 100, $key)
                : $card->getWeight();
        }

        return new Combination(self::FLUSH, $comboWeight, $cards);
    }

    protected function straightCombo(CardCollection $cards)
    {
        $comboWeight = 500;

        foreach ($cards as $key => $card) {
            $comboWeight += $key
                ? $card->getWeight() / pow( 100, $key)
                : $card->getWeight();
        }

        return new Combination(self::STRAIGHT, 500, $cards);
    }

    protected function threeOfAKindCombo(CardCollection $tripple, CardCollection $kickers)
    {
        $comboWeight = 400 + $tripple[0]->getWeight()
            + $kickers[0]->getWeight()/100
            + $kickers[1]->getWeight()/10000;

        return new Combination(self::THREE_OF_A_KIND, $comboWeight, $tripple->merge($kickers));
    }

    protected function twoPairCombo(CardCollection $pairs, Card $kicker)
    {
        $comboWeight = 300 + $pairs[0]->getWeight()
            + $pairs[2]->getWeight()/100
            + $kicker->getWeight() / 10000;

        return new Combination(self::TWO_PAIR, $comboWeight, $pairs->push($kicker));
    }

    protected function onePairCombo(CardCollection $pair, CardCollection $cards)
    {
        $weight = 200 + $pair[0]->getWeight();

        foreach ($cards as $key => $card) {
            $weight += $card->getWeight() / pow( 100, $key + 1);
        }

        return new Combination(self::ONE_PAIR, $weight, $cards->merge($pair));
    }

    protected function kickerCombo(CardCollection $cards)
    {
        $weight = 100;

        foreach ($cards as $key => $card) {
            $weight += $key
                ? $card->getWeight() / pow( 100, $key)
                : $card->getWeight();
        }

        return new Combination(self::HIGH_CARD, $weight, $cards);
    }
}