<?php

namespace App\Proj;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Player
 */
class PlayerTest extends TestCase
{
    /**
     * Test create Dealer object and verify that it's done correctly
     */
    public function testCreatePlayerObject(): void
    {
        $player = new Player();
        $this->assertInstanceOf(Player::class, $player);
    }

    /**
     * Test player hands empty from start
     */
    public function testGetHandsWithoutSetup(): void
    {
        // skapa instans av Dealer klass
        $player = new Player();

        // hämta spelarhand
        $playerHand = $player->getHands();

        // kontrollera att att spelarhänder är tom
        $this->assertEmpty($playerHand);
    }

    /**
     * Test player hands after setup
     */
    public function testGetHandsWithSetup(): void
    {
        // skapa instans av Dealer + Deck klass
        $player = new Player();
        $deck = new Deck();

        // kalla på setup metod, sätt antal händer till 2
        $player->setupHands($deck, 2);

        // kontrollera att det finns 2 händer
        $this->assertCount(2, $player->getHands());

        // kontrollera att händerna innehåller vars 2 kort
        $this->assertCount(2, $player->getHand(0));
        $this->assertCount(2, $player->getHand(1));
    }

    /**
     * Test add cards to Player hand
     */
    public function testAddCardToHand(): void
    {
        // skapa instans av Dealer + Deck klass
        $player = new Player();
        $deck = new Deck();

        // lägg till 2 kort i spelarhand
        $player->addCardToHand($deck, 0);
        $player->addCardToHand($deck, 0);

        // kontrollera att antal kort i spelarhanden är 2
        $this->assertCount(2, $player->getHand(0));
    }
}