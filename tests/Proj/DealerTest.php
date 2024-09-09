<?php

namespace App\Proj;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dealer
 */
class DealerTest extends TestCase
{
    /**
     * Test create Dealer object and verify that it's done correctly
     */
    public function testCreateDealerObject(): void
    {
        // skapa instans av Dealer klass
        $dealer = new Dealer();

        // kontrollera att variabeln innehåller en instans av Dealer klassen
        $this->assertInstanceOf(Dealer::class, $dealer);
    }

    /**
     * Test dealer hand empty from start
     */
    public function testGetHandWithoutCards(): void
    {
        // skapa instans av Dealer klass
        $dealer = new Dealer();

        $dealerHand = $dealer->getHand();

        // kontrollera att att dealer hand är tom
        $this->assertEmpty($dealerHand);
    }

    /**
     * Test add card to Dealer hand
     */
    public function testGetHandWithCards(): void
    {
        // skapa instans av Dealer + Deck klass
        $dealer = new Dealer();
        $deck = new Deck();

        // dra 2 kort och lägg i dealerns hand
        $card1 = $deck->drawCard();
        $card2 = $deck->drawCard();
        $dealer->addCard($card1);
        $dealer->addCard($card2);

        // kontrollera att dealerns hand innehåller 2 kort
        $this->assertCount(2, $dealer->getHand());

        // kontrollera att dealerns kort är samma som dragits ur kortleken
        $this->assertSame($card1, $dealer->getHand()[0]);
        $this->assertSame($card2, $dealer->getHand()[1]);
    }
}