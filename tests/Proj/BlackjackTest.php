<?php

namespace App\Proj;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Blackjack
 */
class BlackajackTest extends TestCase
{
    /**
     * Test construct Blackjack object and check for expected properties
     */
    public function testInitGame(): void
    {
        // skapa instans av Blackjack klassen
        $blackjack = new Blackjack();

        // kontrollera att instanser av klasserna Deck, Player och Dealer har skapats
        $this->assertInstanceOf(Deck::class, $blackjack->getDeck());
        $this->assertInstanceOf(Player::class, $blackjack->getPlayer());
        $this->assertInstanceOf(Dealer::class, $blackjack->getDealer());
    }
}