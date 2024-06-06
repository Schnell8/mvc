<?php

namespace App\Game;

/**
 * This file contains the Bank class
 */

/**
 * Class Bank
 *
 * Represents the bank inside the game
 * Provides method to add cards to hand and access bank object
 */
class Bank
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
     * Get the bank's hand
     *
     * @return Card[] Array of Card objects
     */
    public function getHand(): array
    {
        return $this->hand;
    }
}
