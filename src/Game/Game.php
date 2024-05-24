<?php

namespace App\Game;

require_once 'Player.php'; // Include the Player class file
require_once 'Bank.php'; // Include the Bank class file
require_once 'Deck.php'; // Include the Deck class file

class Game
{
    /**
    * @var Player The player in the game.
    */
    private Player $player;
    /**
    * @var Bank The bank in the game.
    */
    private Bank $bank;
    /**
    * @var Deck Deck of cards used in game.
    */
    private Deck $deck;

    /**
     * Game constructor.
     * Initializes the player, bank, and deck, and shuffles the deck.
     */
    public function __construct()
    {
        $this->player = new Player();
        $this->bank = new Bank();
        $this->deck = new Deck();
        $this->deck->shuffle();
    }

    /**
     * Starts the game by dealing initial cards.
     */
    public function startGame(): void
    {
        $this->dealInitialCards();
    }

    /**
     * Deals initial cards to the player.
     */
    private function dealInitialCards(): void
    {
        $card = $this->deck->drawCard();
        $this->player->takeCard($card);
    }

    /**
     * Get deck used in the game.
     *
     * @return Deck Deck used in game.
     */
    public function getDeck(): Deck
    {
        return $this->deck;
    }

    /**
     * Get player's hand.
     *
     * @return string[] Player's hand.
     */
    public function getPlayerHand(): array
    {
        return $this->player->getHand();
    }

    /**
     * Get bank's hand.
     *
     * @return string[] Bank's hand.
     */
    public function getBankHand(): array
    {
        return $this->bank->getHand();
    }
}
