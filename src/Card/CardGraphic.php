<?php

namespace App\Card;

class CardGraphic extends Card
{
    private $representation = [
        1 => '🂱',
        2 => '🂲',
        3 => '🂳',
        4 => '🂴',
        5 => '🂵',
        6 => '🂶',
        7 => '🂷',
        8 => '🂸',
        9 => '🂹',
        10 => '🂺',
        11 => '🂼',
        12 => '🂽',
        13 => '🂾',
        14 => '🃁',
        15 => '🃂',
        16 => '🃃',
        17 => '🃄',
        18 => '🃅',
        19 => '🃆',
        20 => '🃇',
        21 => '🃈',
        22 => '🃉',
        23 => '🃊',
        24 => '🃌',
        25 => '🃍',
        26 => '🃎',
        27 => '🂡',
        28 => '🂢',
        29 => '🂣',
        30 => '🂤',
        31 => '🂥',
        32 => '🂦',
        33 => '🂧',
        34 => '🂨',
        35 => '🂩',
        36 => '🂪',
        37 => '🂬',
        38 => '🂭',
        39 => '🂮',
        40 => '🃑',
        41 => '🃒',
        42 => '🃓',
        43 => '🃔',
        44 => '🃕',
        45 => '🃖',
        46 => '🃗',
        47 => '🃘',
        48 => '🃙',
        49 => '🃚',
        50 => '🃜',
        51 => '🃝',
        52 => '🃞',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public static function cards()
    {
        $values = array('2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A');
        $suits  = array('S', 'H', 'D', 'C');

        $cards = array();
        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $cards[] = $value . $suit;
            }
        }

        return $cards;
    }
    public function getSingleRepresentation(int $index): string
    {
        return $this->representation[$index];
    }

    public function getAsString(): string
    {
        return $this->representation[$this->value];
    }
}
