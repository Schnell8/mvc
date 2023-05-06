<?php

namespace App\Card;

class DeckOfCards
{
    private $deck = [];

    public function addCard(string $card): void
    {
        $this->deck[] = $card;
    }

    public function getDeck(): array
    {
        return $this->deck;
    }

    public function getNumberCards(): int
    {
        return count($this->deck);
    }

    public static function deckForJson(): array
    {
        $suits  = array('H', 'D', 'S', 'C');
        $values = array('A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K');

        $deck = array();
        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $deck[] = $value . $suit;
            }
        }

        return $deck;
    }
}
