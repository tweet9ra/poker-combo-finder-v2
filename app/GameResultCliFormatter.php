<?php


namespace App;


use App\Card\CardCollection;

class GameResultCliFormatter
{
    /**
     * @var array
     */
    protected $gameResult;

    public function __construct(array $gameResult)
    {
        $this->gameResult = $gameResult;
    }

    public function format() : string
    {
        $strResult = '';

        foreach ($this->gameResult as $key => $result) {
            /**
             * @var Player $player
             * @var Combination $combination
             */
            $player = $result['player'];
            $combination = $result['combination'];

            $playerId = $player->getId();

            $comboName = $combination->getName();

            $usedCards = $combination->getUsedCards();
            $usedCardsSignatures = $usedCards
                ->sortByValue('asc')
                ->pluckSignatures();

            $strUsedCards = implode(' ', $usedCardsSignatures);

            $usedPlayerCards = $player->getCards()
                ->intersect($usedCards)
                ->sortByValue('desc')
                ->pluckSignatures();
            $strUsedPlayerCards = implode(' ', $usedPlayerCards);

            $strResult .= "$playerId $comboName [$strUsedCards] [$strUsedPlayerCards]\n";
        }

        return $strResult;
    }
}