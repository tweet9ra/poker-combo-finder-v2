<?php

namespace App;

use App\Card\CardFactory;

/**
 * Обрабатывает консольные входные параметры
 *      php index.php --board=7dAcJc4d --p1=7sAh --p2=JsJd --p5=KsQs
 */
class CliInputReader
{
    /**
     * @param array $input
     * @return Table
     * @throws \Exception
     */
    public function readTable($input) : Table
    {
        // Удаляем название файла
        array_shift($input);

        // Создаём стол из входных параметров
        $re = '/--(?P<paramName>p[1-9]+|\b(?=\w)board)=(?P<cards>[a-zA-Z0-9]+)/m';
        $players = [];

        $cardFactory = new CardFactory;

        foreach ($input as $inputParameter) {
            preg_match_all($re, $inputParameter, $matches, PREG_SET_ORDER);

            $paramName = $matches[0]['paramName'];
            $cards = $matches[0]['cards'];

            if (!$paramName || !$cards) {
                throw new \Exception("Некорректный входной параметр: $inputParameter");
            }

            $cardsObjects = $cardFactory->createCollectionFromStringOfSignatures($cards);

            if ($paramName === 'board') {
                $board = $cardsObjects;
            } else {
                $players[] = new Player (
                    $paramName,
                    $cardsObjects
                );
            }
        }

        if (!isset($board)) {
            throw new \Exception("Не указаны карты на столе в параметре «board»");
        }

        if (count($players) < 2) {
            throw new \Exception("В игре должны участвовать как минимум 2 игрока");
        }

        return new Table($board, $players);
    }
}