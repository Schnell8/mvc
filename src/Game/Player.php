<?php

namespace App\Game;

/**
 * This file contains the Player class
 */

/**
 * Class Player
 *
 * Represents the player inside the game
 * Provides method to add cards to hand and access player object
 */
class Player
{
    /**
     * @var Card[] Array of Card objects
     */
    private array $hand = [];

    /**
     * Add a card to the bank's hand
     *
     * @param Card $card
     */
    public function addCard(Card $card): void
    {
        $this->hand[] = $card;
    }

    /**
     * Get the player's hand
     *
     * @return Card[] Array of Card objects
     */
    public function getHand(): array
    {
        return $this->hand;
    }
}
