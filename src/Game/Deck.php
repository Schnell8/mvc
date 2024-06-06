<?php

namespace App\Game;

/**
 * This file contains the Deck class
 */

/**
 * Class Deck
 *
 * Represents a deck of 52 standard playing cards
 * Provides methods to initialize, shuffle, draw cards, recreate deck in json format and access deck object
 */
class Deck
{
    /**
     * @var string[] Array of strings
     */
    private array $deck = [];

    /**
     * Deck constructor
     * Initializes deck calling the initializeDeck method
     */
    public function __construct()
    {
        $this->initializeDeck();
    }

    /**
     * Initializes deck
     * Creates deck holding standard 52 playing cards
     */
    private function initializeDeck(): void
    {
        $suits = ['♠', '♣', '♥', '♦'];
        $values = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];

        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $this->deck[] = $value . $suit;
            }
        }
    }

    /**
     * Get deck in JSON format
     *
     * @return string[] Array of strings in JSON format
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

    /**
     * Get deck
     *
     * @return string[] Array of strings
     */
    public function getDeck(): array
    {
        return $this->deck;
    }

    /**
     * Shuffle deck
     */
    public function shuffle(): void
    {
        shuffle($this->deck);
    }

    /**
     * Draw card from deck
     * Raises Exception if deck is empty
     *
     * @return Card Card object
     */
    public function drawCard(): Card
    {
        $card = array_pop($this->deck);
        if ($card === null) {
            throw new \LogicException('No more cards in deck.');
        }
        return new Card($card);
    }
}
