<?php

namespace App\Proj;

/**
 * This file contains the Bank class
 */

/**
 * Class Dealer
 *
 * Represents the dealer inside the blackjack game
 * Provides method to add cards to hand and access dealer object
 */
class Dealer
{
    /**
     * @var Card[] Array of Card objects
     */
    private array $hand = [];

    /**
     * Add a card to the dealer hand
     *
     * @param Card $card
     */
    public function addCard(Card $card): void
    {
        $this->hand[] = $card;
    }

    /**
     * Get the dealer hand
     *
     * @return Card[] Array of Card objects
     */
    public function getHand(): array
    {
        return $this->hand;
    }
}
