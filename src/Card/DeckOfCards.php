<?php

namespace App\Card;

class DeckOfCards
{
    /**
     * @var string[] Array of strings
     */
    private array $deck = [];

    /**
     * Adds a card to the deck.
     *
     * @param string $card The card to add to the deck.
     * @return void
     */
    public function addCard(string $card): void
    {
        $this->deck[] = $card;
    }

    /**
     * Get deck.
     *
     * @return string[] Array of strings
     */
    public function getDeck(): array
    {
        return $this->deck;
    }

    public function getNumberCards(): int
    {
        return count($this->deck);
    }

    /**
     * Get deck in JSON format.
     *
     * @return string[] Array of strings in JSON format.
     */
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
