<?php

namespace App\Game;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
{
    /**
     * Test create Card object and verify that it's done correctly
     */
    public function testCreateCardObject(): void
    {
        $cardValue = 'A♠';
        $card = new Card($cardValue);

        $this->assertInstanceOf(Card::class, $card);
    }

    /**
     * Test that getCard returns correct card
     */
    public function testGetCard(): void
    {
        $cardValue = '7♥';
        $card = new Card($cardValue);

        $this->assertSame($cardValue, $card->getCard());
    }

    /**
     * Test that __toString returns correct string representation
     */
    public function test__toString(): void
    {
        $cardValue = '10♣';
        $card = new Card($cardValue);

        $this->assertSame($cardValue, (string)$card);
    }
}