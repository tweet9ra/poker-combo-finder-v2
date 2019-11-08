<?php

namespace App\Card;

use App\Card\Card;
use App\Card\CardCollection;
use App\Config;
use App\Exceptions\InvalidCardSignatureException;

class CardFactory
{
    /**
     * @param string $cardsString
     * @return CardCollection
     * @throws InvalidCardSignatureException
     */
    public function createCollectionFromStringOfSignatures(string $cardsString) : CardCollection
    {
        $availableValues = implode('|', Config::get('availableCardValues'));
        $availableSuits = implode('|', Config::get('availableSuits'));
        $cardRegex = "/(?P<value>$availableValues)(?P<suit>[$availableSuits])/m";

        preg_match_all($cardRegex, $cardsString, $cardMatches, PREG_SET_ORDER);

        $cards = new CardCollection;
        foreach ($cardMatches as $cardInfo) {
            if (!$cardInfo['suit'] || !$cardInfo['value']) {
                throw new InvalidCardSignatureException("Некорректная карта: $cardInfo[0]");
            }

            $cards[] = new Card($cardInfo['suit'], $cardInfo['value']);
        }

        return $cards;
    }
}