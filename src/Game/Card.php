<?php

namespace App\Game;

/**
 * This file contains the Card class
 */

/**
 * Class Card
 *
 * Represents a playing card
 * Provides method to access the card value and string representation
 */
class Card
{
    /**
     * @var string Card value
     */
    private string $card;

    /**
     * Card constructor
     * Initializez card with given value
     *
     * @param string $card Value for card
     */
    public function __construct(string $card)
    {
        $this->card = $card;
    }

    /**
     * Get card value
     *
     * @return string Card value
     */
    public function getCard(): string
    {
        return $this->card;
    }

    /**
     * Get string representation for card
     *
     * @return string String representation for card
     */
    public function __toString(): string
    {
        return $this->card;
    }
}
