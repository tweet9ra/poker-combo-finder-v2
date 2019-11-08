<?php
$data = [
    // Флеш рояль у игрока
    'Royal flush player' => [
        'command' => '--board=10dJdQdKd --p2=AdAh --p1=2c5d',
        'result' => [
            'p2 Royal Flush [10d Jd Qd Kd Ad] [Ad]',
            'p1 Flush [5d 10d Jd Qd Kd] [5d]'
        ]
    ],
    // Флеш рояль на доске
    'Royal flush on board' => [
        'command' => '--board=10dJdQdKdAd --p1=AhAc --p2=2c5d',
        'result' => [
            'p1 Royal Flush [10d Jd Qd Kd Ad] []',
            'p2 Royal Flush [10d Jd Qd Kd Ad] []'
        ]
    ],
    // Стрит флэш на доске
    'Straight flush on board' => [
        'command' => '--board=10dJdQdKd9d --p1=2c5d --p2=AhAc',
        'result' => [
            'p1 Straight Flush [9d 10d Jd Qd Kd] []',
            'p2 Straight Flush [9d 10d Jd Qd Kd] []',
        ]
    ],
    // Стрит флэш с тузом в начале
    'Straight flush A start' => [
        'command' => '--board=Ad2d3d4d --p1=5d5c --p2=AhAc',
        'result' => [
            'p1 Straight Flush [2d 3d 4d 5d Ad] [5d]',
            'p2 Three of a Kind [3d 4d Ac Ad Ah] [Ac Ah]',
        ]
    ],
    // Разный стрит флэш у игроков
    'Straight flush 2x' => [
        'command' => '--board=3d4d5d6d --p1=2d5c --p2=7dAc',
        'result' => [
            'p2 Straight Flush [3d 4d 5d 6d 7d] [7d]',
            'p1 Straight Flush [2d 3d 4d 5d 6d] [2d]',
        ]
    ],
    // Четверка на столе, старшенство по киккеру
    'Four of a kind board' => [
        'command' => '--board=10d10c10s10h --p1=2d5c --p2=7dAc',
        'result' => [
            'p2 Four of a Kind [10c 10d 10h 10s Ac] [Ac]',
            'p1 Four of a Kind [5c 10c 10d 10h 10s] [5c]',
        ]
    ],
    // Четверка у игрока
    'Four of a kind player' => [
        'command' => '--board=10d10c10s --p1=10h5c --p2=7dAc',
        'result' => [
            'p1 Four of a Kind [5c 10c 10d 10h 10s] [10h 5c]',
            'p2 Three of a Kind [7d 10c 10d 10s Ac] [Ac 7d]',
        ]
    ],
    // Фулл хаус
    'Full house' => [
        'command' => '--board=2h3c3h --p1=2s3s --p2=KdKs',
        'result' => [
            'p1 Full House [2h 2s 3c 3h 3s] [3s 2s]',
            'p2 Two pair [2h 3c 3h Kd Ks] [Kd Ks]'
        ]
    ],
    // Одинаковый фулл хаус
    'Full house 2 similar weight' => [
        'command' => '--board=2h3c3h --p1=2s3s --p2=2c3d',
        'result' => [
            'p1 Full House [2h 2s 3c 3h 3s] [3s 2s]',
            'p2 Full House [2c 2h 3c 3d 3h] [3d 2c]'
        ]
    ],
    // Флеш на столе
    'Flush board' => [
        'command' => '--board=2hKh10h9hAh --p1=Ac9s --p2=KdKs',
        'result' => [
            'p1 Flush [2h 9h 10h Kh Ah] []',
            'p2 Flush [2h 9h 10h Kh Ah] []'
        ]
    ],
    // Флеш у игрока
    'Flush player' => [
        'command' => '--board=Kh10h9hAh --p1=2h9s --p2=KdKs',
        'result' => [
            'p1 Flush [2h 9h 10h Kh Ah] [2h]',
            'p2 Three of a Kind [10h Kd Kh Ks Ah] [Kd Ks]'
        ]
    ],
    // Флеши у двух игроков
    'Flush 2 players' => [
        'command' => '--board=10h9h5h --p1=KhQh --p2=Ah2h',
        'result' => [
            'p2 Flush [2h 5h 9h 10h Ah] [Ah 2h]',
            'p1 Flush [5h 9h 10h Qh Kh] [Kh Qh]'
        ]
    ],
    // 2 пары у игрока
    'Two pairs player' => [
        'command' => '--board=2h3c4s --p1=2c3h --p2=5d5h',
        'result' => [
            'p1 Two pair [2c 2h 3c 3h 4s] [3h 2c]',
            'p2 One pair [2h 3c 4s 5d 5h] [5d 5h]'
        ]
    ],
    // 2 разные пары у 2 игроков
    'Two pairs 2 players' => [
        'command' => '--board=2h3c4s --p1=2c3h --p2=2s4h',
        'result' => [
            'p2 Two pair [2h 2s 3c 4h 4s] [4h 2s]',
            'p1 Two pair [2c 2h 3c 3h 4s] [3h 2c]',
        ]
    ],
    // 2 разные пары у 2 игроков, одинаковая старшая пара
    'Two pairs 2 players same hight pair' => [
        'command' => '--board=2h3c4s --p1=3h4c --p2=2s4h',
        'result' => [
            'p1 Two pair [2h 3c 3h 4c 4s] [4c 3h]',
            'p2 Two pair [2h 2s 3c 4h 4s] [4h 2s]'
        ]
    ],
    // 2 пары на доске
    'Two pairs on board' => [
        'command' => '--board=2h2c3s3h --p1=AhJc --p2=AsJh',
        'result' => [
            'p1 Two pair [2c 2h 3h 3s Ah] [Ah]',
            'p2 Two pair [2c 2h 3h 3s As] [As]'
        ]
    ],
    // 1 пара против старшей
    'Pair' => [
        'command' => '--board=2h3c5s7h --p1=2cJc --p2=AsJh',
        'result' => [
            'p1 One pair [2c 2h 5s 7h Jc] [Jc 2c]',
            'p2 High card [3c 5s 7h Jh As] [As Jh]'
        ]
    ],
    // 1 пара против пары
    'Pair vs pair' => [
        'command' => '--board=2h3c5s7h --p1=2cJc --p2=3sJh',
        'result' => [
            'p2 One pair [3c 3s 5s 7h Jh] [Jh 3s]',
            'p1 One pair [2c 2h 5s 7h Jc] [Jc 2c]'
        ]
    ],
    //Старшая карта
    'High card' => [
        'command' => '--board=2h3c9s10h --p1=4cJc --p2=AsJh',
        'result' => [
            'p2 High card [3c 9s 10h Jh As] [As Jh]',
            'p1 High card [3c 4c 9s 10h Jc] [Jc 4c]'
        ]
    ],
    // Тесты из ТЗ (исправленные)
    'default 1' => [
        'command' => '--board=7dAcJc4d --p1=7sAh --p2=JsJd --p3=KsQs',
        'result' => [
            'p2 Three of a Kind [7d Jc Jd Js Ac] [Jd Js]',
            'p1 Two pair [7d 7s Jc Ac Ah] [Ah 7s]',
            'p3 High card [7d Jc Qs Ks Ac] [Ks Qs]'
        ]
    ],
    'default 2' => [
        'command' => '--board=5s8s9s --p1=9d9c --p2=As2s --p3=7d6h',
        'result' => [
            'p2 Flush [2s 5s 8s 9s As] [As 2s]',
            'p3 Straight [5s 6h 7d 8s 9s] [7d 6h]',
            'p1 Three of a Kind [5s 8s 9c 9d 9s] [9c 9d]'
        ]
    ],
];
$startTime = microtime(true);
$passed = 0;
foreach ($data as $testName => $testData) {
    $command = "php index.php {$testData['command']}";
    $result = [];
    exec($command, $result);
    if ($result == $testData['result']) {
        $passed++;
        $resultString = '+';
    } else {
        $resultString = '---';
    }
    echo "$resultString $testName\n";
}
echo "\n$passed/".count($data)." tests passed. Time: ".(microtime(true) - $startTime);