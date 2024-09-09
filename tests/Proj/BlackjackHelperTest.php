<?php

namespace App\proj;

use App\Help_Functions\BlackjackHelper;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class GameHelper
 */
class HelpTest extends TestCase
{
    /**
     * Test if function returns correct value for dealer hand
     */
    public function testCalculateDealerHandValue(): void
    {
        // skapa instans av BlackjackHelper klassen
        $blackjackHelper = new BlackjackHelper();

        // hand 1
        $dealerHand1 = ['queen_of_hearts', '7_of_clubs', '3_of_spades'];

        // förväntat värde
        $expectedDealerHandValue1 = 10 + 7 + 3;

        // kontrollera att värdena är lika
        $this->assertEquals($expectedDealerHandValue1, $blackjackHelper->calculateDealerHandValue($dealerHand1));

        // hand 2
        $dealerHand2 = ['ace_of_hearts', 'ace_of_diamonds', 'ace_of_clubs', '6_of_hearts'];
        
        // förväntat värde
        $expectedDealerHandValue2 = 11 + 1 + 1 + 6;

        // kontrollera att värdena är lika
        $this->assertEquals($expectedDealerHandValue2, $blackjackHelper->calculateDealerHandValue($dealerHand2));
    }

    /**
     * Test if function returns correct value for player hands
     */
    public function testCalculatePlayerHandsValues(): void
    {
        // skapa instans av BlackjackHelper klassen
        $blackjackHelper = new BlackjackHelper(); 

        // bestäm innehåll i spelarhänder
        $playerHands = [
            ['king_of_hearts', '5_of_clubs'],
            ['jack_of_diamonds', '3_of_spades', '4_of_hearts'],
            ['ace_of_spades', 'queen_of_clubs']
        ];

        // förväntade värden
        $expectedPlayerHandsValues = [
            10 + 5,
            10 + 3 + 4,
            11 + 10
        ];

        // kontrollera att värdena är lika
        $this->assertEquals($expectedPlayerHandsValues, $blackjackHelper->calculatePlayerHandsValues($playerHands));

    }

    /**
     * Test if function returns correct results
     */
    public function testCalculateResults(): void
    {
        // skapa instans av BlackjackHelper klassen
        $blackjackHelper = new BlackjackHelper(); 

        // resultat för spelare och dealer
        $playerResults = [
            15,
            20,
            17
        ];
        $dealerResult = 17;

        // förväntade värden
        $expectedResults = [
            'Loss',
            'Win',
            'Loss'
        ];

        // kontrollera att värdena är lika
        $this->assertEquals($expectedResults, $blackjackHelper->calculateResults($playerResults, $dealerResult));
    }
}