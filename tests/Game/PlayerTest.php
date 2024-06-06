<?php

namespace App\Game;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Player
 */
class PlayerTest extends TestCase
{
    /**
     * Test create Bank object and verify that it's done correctly
     */
    public function testCreatePlayerObject(): void
    {
        $bank = new Bank();
        $this->assertInstanceOf(Bank::class, $bank);
    }

    /**
     * Test add card to Player's hand
     */
    public function testAddCardToPlayerHand(): void
    {
        $player = new Player();
        $deck = new Deck();

        $card = $deck->drawCard();
        $player->addCard($card);

        $this->assertCount(1, $player->getHand());
        $this->assertSame($card, $player->getHand()[0]);
    }
}