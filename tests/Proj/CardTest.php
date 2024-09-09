<?php

namespace App\Proj;

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
        // skapa instans av Card klass
        $cardValue = 'ace_of_hearts';
        $card = new Card($cardValue);

        // kontrollera att det är en instans av Card klassen
        $this->assertInstanceOf(Card::class, $card);
    }

    /**
     * Test that getCard returns correct card
     */
    public function testGetCard(): void
    {
        // skapa instans av Card klass
        $cardValue = 'seven_of_hearts';
        $card = new Card($cardValue);

        // kontrollera att rätt kort lades till
        $this->assertSame($cardValue, $card->getCard());
    }

    /**
     * Test that __toString returns correct string representation
     */
    public function testToString(): void
    {
        // skapa instans av Card klass
        $cardValue = '10_of_clubs';
        $card = new Card($cardValue);

        // kontrollera att strängarna matchar
        $this->assertSame($cardValue, (string)$card);
    }
}