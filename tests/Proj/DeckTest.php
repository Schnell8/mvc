<?php

namespace App\Proj;

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
        // skapa instans av Deck klassen
        $deck = new Deck();

        // kontrollera att en instans av Deck klassen har skapats
        $this->assertInstanceOf(Deck::class, $deck);

        // kontrollera att kortleken innehåller 52 kort
        $this->assertCount(52, $deck->getDeck());
    }

    /**
     * Test draw card from deck
     */
    public function testDrawCardFromDeck(): void
    {
        // skapa instans av Deck klassen
        $deck = new Deck();

        // kontrollera att kortleken innehåller 52 kort
        $this->assertCount(52, $deck->getDeck());

        // dra 2 kort från kortleken
        $deck->drawCard();
        $deck->drawCard();

        // kontrollera att kortleken innehåller 50 kort
        $this->assertCount(50, $deck->getDeck());
    }

    /**
     * Test draw card from empty deck
     */
    public function testDrawCardFromEmptyDeck(): void
    {
        // skapa instans av Deck klassen
        $deck = new Deck();

        // dra 52 kort
        for ($i = 0; $i < 52; $i++) {
            $deck->drawCard();
        }

        // kontrollera att Exception lyfts vid dragning av tom kortlek
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('No more cards in deck.');
        $deck->drawCard();
    }

    /**
     * Test shuffle deck
     */
    public function testShuffleDeck(): void
    {
        // skapa instans av Deck klassen
        $deck = new Deck();

        // variabel för att hålla original kortlek
        $originalDeck = $deck->getDeck();

        // blanda kortlek
        $deck->shuffle();

        // variabel för att hålla blandad kortlek
        $shuffledDeck = $deck->getDeck();

        // kontrollera att kortlekarna inte är samma
        $this->assertNotSame($originalDeck, $shuffledDeck, 'Deck not shuffled.');
    }
}