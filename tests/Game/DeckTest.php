<?php

namespace App\Game;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Deck
 */
class DeckTest extends TestCase
{
    /**
     * Test construct object and check for expected properties
     */
    public function testCreateDeckObject(): void
    {
        $deck = new Deck();
        $this->assertInstanceOf(Deck::class, $deck);
        $this->assertCount(52, $deck->getDeck());
    }

    /**
     * Test deck for json
     */
    public function testDeckforJson(): void
    {
        $deck = new Deck();
        $expectedDeck = [
            'AH', '2H', '3H', '4H', '5H', '6H', '7H', '8H', '9H', '10H', 'JH', 'QH', 'KH',
            'AD', '2D', '3D', '4D', '5D', '6D', '7D', '8D', '9D', '10D', 'JD', 'QD', 'KD',
            'AS', '2S', '3S', '4S', '5S', '6S', '7S', '8S', '9S', '10S', 'JS', 'QS', 'KS',
            'AC', '2C', '3C', '4C', '5C', '6C', '7C', '8C', '9C', '10C', 'JC', 'QC', 'KC',
        ];

        $jsonDeck = $deck->deckForJson();
        $this->assertSame($expectedDeck, $jsonDeck);
    }

    /**
     * Test draw card from deck
     */
    public function testDrawCardFromDeck(): void
    {
        $deck = new Deck();

        $this->assertCount(52, $deck->getDeck()); // before draw
        $deck->drawCard();
        $this->assertCount(51, $deck->getDeck()); // after draw
    }

    /**
     * Test draw card from empty deck
     */
    public function testDrawCardFromEmptyDeck(): void
    {
        $deck = new Deck();

        for ($i = 0; $i < 52; $i++) {
            $deck->drawCard(); // draw all 52 cards
        }

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('No more cards in deck.');

        $deck->drawCard(); // draw card when deck is empty
    }

    /**
     * Test shuffle deck
     */
    public function testShuffleDeck(): void
    {
        $deck = new Deck();
        $originalDeck = $deck->getDeck(); // before shuffle

        $deck->shuffle();
        $shuffledDeck = $deck->getDeck(); // after shuffle

        $this->assertNotSame($originalDeck, $shuffledDeck, 'Deck not shuffled.');
    }
}