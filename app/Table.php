<?php


namespace App;

use App\Card\Card;
use App\Card\CardCollection;
use App\Exceptions\DuplicatedCardException;

/**
 * Игровой стол
 */
class Table
{
    /** @var CardCollection */
    protected $boardCards;

    /** @var Player[] */
    protected $players;

    /**@var array */
    protected $usedCards = [];

    /**
     * @var AbstractCombinationFinder $combinationFinder
     */
    protected $combinationFinder;

    public function __construct(CardCollection $boardCards, array $players)
    {
        $this->setTableState($boardCards, $players);

        $combinationFinderClassName = Config::get('combinationFinder');
        $this->combinationFinder = new $combinationFinderClassName();
    }

    /**
     * Установить состояние стола: карты на столе, на руках
     * @param CardCollection $boardCards
     * @param Player[] $players
     * @throws \InvalidArgumentException
     * @throws DuplicatedCardException
     */
    public function setTableState(CardCollection $boardCards, array $players)
    {
        $this->usedCards = [];

        $this->boardCards = $boardCards;
        $duplicatedBoardCards = $this->cardsDuplicates($boardCards);

        if ($duplicatedBoardCards) {
            throw new DuplicatedCardException("Найдены дубликаты карт на столе: "
                .implode(',', $duplicatedBoardCards));
        }

        foreach ($players as $player) {
            if (!$player instanceof Player) {
                throw new \InvalidArgumentException("Параметр players должен быть массивом объектов " .Player::class);
            }

            $duplicatedPlayerCards = $this->cardsDuplicates($player->getCards());
            if ($duplicatedPlayerCards) {
                throw new DuplicatedCardException(
                    'Найдены дубликаты карт у игрока '.$player->getId()
                    .': '.implode(',', $duplicatedPlayerCards)
                );
            }
        }

        $this->players = $players;
    }

    /**
     * Проверка дубликтов карт
     * @param CardCollection $cards
     * @return array|false
     */
    protected function cardsDuplicates(CardCollection $cards)
    {
        $usedCards = array_merge($this->usedCards, array_map(function (Card $card) {
            return $card->getSignature();
        }, $cards->toArray()));

        $cardsCountBySignature = array_count_values($usedCards);
        $duplicates = array_filter($cardsCountBySignature, function ($count) {
            return $count > 1;
        });

        if ($duplicates) {
            return array_keys($duplicates);
        }

        $this->usedCards = $usedCards;

        return false;
    }

    /**
     * Получить результат игры в формате массива игрок+комбинация
     * @return array
     */
    public function gameResult()
    {
        $result = [];

        foreach ($this->players as $player) {
            $boardAndPlayerCards = $player->getCards()->merge($this->boardCards);
            $combination = $this->combinationFinder->find($boardAndPlayerCards);

            $result[] = [
                'player' => $player,
                'combination' => $combination
            ];
        }

        usort($result, function ($res1, $res2) {
             return $res2['combination']->getWeight() <=> $res1['combination']->getWeight();
        });

        return $result;
    }
}