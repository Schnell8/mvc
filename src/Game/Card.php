<?php

namespace App\Game;

class Card
{
    /**
     * @var string Card value
     */
    private string $value;

    /**
     * Card constructor.
     *
     * @param string $value card value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * Get card value.
     *
     * @return string Card value
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Get string representation for card.
     *
     * @return string String representation for card
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
