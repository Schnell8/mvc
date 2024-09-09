<?php

namespace App\Proj;

require_once 'Player.php'; // Include the Player class file
require_once 'Dealer.php'; // Include the Dealer class file
require_once 'Deck.php'; // Include the Deck class file

/**
 * This file contains the Blackjack class
 */

/**
 * Class Blackjack
 *
 * Represents a blackjack in which a player competes against a dealer using a deck of cards
 * Provides methods to start the blackjack, deal initial cards and access player, dealer and deck objects
 */
class Blackjack
{
    /**
    * @var Player The player in the blackjack
    */
    private Player $player;
    /**
    * @var Dealer The dealer in the blackjack
    */
    private Dealer $dealer;
    /**
    * @var Deck Deck of cards used in blackjack
    */
    private Deck $deck;

    /**
     * Game constructor
     * Initializes the player, dealer, and deck, and shuffles the deck
     */
    public function __construct()
    {
        $this->player = new Player();
        $this->dealer = new Dealer();
        $this->deck = new Deck();
        $this->deck->shuffle();
    }

    /**
     * Get player used in game
     *
     * @return Player Player used in game
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * Get dealer used in game
     *
     * @return Dealer Dealer used in game
     */
    public function getDealer(): Dealer
    {
        return $this->dealer;
    }

    /**
     * Get deck used in the game
     *
     * @return Deck Deck used in game
     */
    public function getDeck(): Deck
    {
        return $this->deck;
    }
}
