<?php

use App\TexasHoldemCombinationFinder;

return [
    /**
     * Возможные значения (достоинства) карт в колоде
     * в формате вес => значение
     * вес может быть 0 < weight < 99
     */
    'availableCardValues' => [
        2, 3, 4, 5, 6, 7, 8, 9, 10,
        'J', 'Q', 'K', 'A'
    ],
    /**
     * Возможные масти карт колоде
     */
    'availableSuits' => ['d', 'c', 'h', 's'],
    /**
     * Компонент определения комбинаций
     */
    'combinationFinder' => TexasHoldemCombinationFinder::class
];