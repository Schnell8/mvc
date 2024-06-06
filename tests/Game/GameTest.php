<?php

namespace App\Game;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Game
 */
class GameTest extends TestCase
{
    /**
     * Test construct object and check game for expected properties
     */
    public function testInitGame(): void
    {
        $game = new Game();

        $this->assertInstanceOf(Deck::class, $game->getDeck());
        $this->assertInstanceOf(Player::class, $game->getPlayer());
        $this->assertInstanceOf(Bank::class, $game->getBank());
    }

    /**
     * Test that starting game will deal initial cards to player
     * and none to bank
     */
    public function testStartGame(): void
    {
        $game = new Game();
        $game->startGame();

        $this->assertCount(1, $game->getPlayer()->getHand());
        $this->assertCount(0, $game->getBank()->getHand());
    }
}