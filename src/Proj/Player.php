<?php

namespace App\Proj;

/**
 * This file contains the Player class
 */

/**
 * Class Player
 *
 * Represents the player inside the game
 * Provides method to add cards to hand and access player object
 */
class Player
{
    /**
     * @var array Array which will hold the Card objects
     */
    private array $hands = [];

    /**
     * Set up hands
     *
     * @param int $numberofHands Number of hands the player will have
     * @param Deck $deck Deck to draw cards from
     */
    public function setupHands(Deck $deck, int $numberOfHands): void
    {
        // add empty arrays for each hand to hands
        $this->hands = array_fill(0, $numberOfHands, []);

        // Add two cards to each hand
        for ($i = 0; $i < $numberOfHands; $i++) {
            $this->addCardToHand($deck, $i);
            $this->addCardToHand($deck, $i);
        }
    }

    /**
     * Add card to specified hand
     *
     * @param Deck $deck Deck to draw cards from
     * @param int $handIndex The index of the hand
     */
    public function addCardToHand(Deck $deck, int $handIndex): void
    {
        $card = $deck->drawCard();
        $this->hands[$handIndex][] = $card;
    }

    /**
     * Get player hands
     *
     * @return Card[] Array of Card objects
     */
    public function getHands(): array
    {
        return $this->hands;
    }

    /**
     * Get specific hand
     *
     * @param int $handIndex Index of the hand
     * @return Card[] Array of Card objects for the specified hand
     */
    public function getHand(int $handIndex): array
    {
        return $this->hands[$handIndex] ?? [];
    }
}
