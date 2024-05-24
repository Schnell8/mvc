<?php

namespace App\Card;

class CardHand
{
    /**
     * @var string[] Array of strings
     */
    private array $hand = [];

    public function addCard(string $card): void
    {
        $this->hand[] = $card;
    }

    /**
     * Get hand.
     *
     * @return string[] Array of strings
     */
    public function getHand(): array
    {
        return $this->hand;
    }
}
