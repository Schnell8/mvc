<?php

namespace App\Game;

class Player
{
    /**
     * @var Card[] Array of Card objects
     */
    private array $hand = [];

    /**
     * Add a card to the bank's hand.
     *
     * @param Card $card
     */
    public function takeCard(Card $card): void
    {
        $this->hand[] = $card;
    }

    /**
     * Get the player's hand.
     *
     * @return Card[] Array of Card objects
     */
    public function getHand(): array
    {
        return $this->hand;
    }
}
