<?php

namespace App\Card;

use App\Card\Card;

class CardHand
{
    private $hand = [];

    public function addCard(string $card): void
    {
        $this->hand[] = $card;
    }

    public function getHand(): array
    {
        return $this->hand;
    }
}
