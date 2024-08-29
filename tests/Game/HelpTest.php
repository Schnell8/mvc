<?php

namespace App\Game;

use App\Help_Functions\GameHelper;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class GameHelper
 */
class HelpTest extends TestCase
{
    /**
     * Test if function returns correct value for hand
     */
    public function testCalculateWinner(): void
    {
        $gameHelper = new GameHelper();

        // 1
        $hand1 = ['Q', '9'];
        $expectedValue1 = 12 + 9;

        $this->assertEquals($expectedValue1, $gameHelper->calculateHandValue($hand1));

        // 2
        $hand2 = ['A', 'A', '3'];
        $expectedValue2 = 14 + 1 + 3;

        $this->assertEquals($expectedValue2, $gameHelper->calculateHandValue($hand2));

        // 3
        $hand3 = ['10', '2', 'J'];
        $expectedValue3 = 10 + 2 + 11;

        $this->assertEquals($expectedValue3, $gameHelper->calculateHandValue($hand3));
    }

    /**
     * Test if function returns correct winner
     */
    public function testDetermineWinner(): void
    {
        $gameHelper = new GameHelper();

        // 1
        $playerHandValue1 = 19;
        $bankHandValue1 = 17;
        $expVal1 = "Player wins!";

        $this->assertEquals($expVal1, $gameHelper->determineWinner($playerHandValue1, $bankHandValue1));

        // 2
        $playerHandValue2 = 25;
        $bankHandValue2 = 22;
        $expVal2 = "It's a tie!";

        $this->assertEquals($expVal2, $gameHelper->determineWinner($playerHandValue2, $bankHandValue2));


        // 3
        $playerHandValue3 = 20;
        $bankHandValue3 = 21;
        $expVal3 = "Bank wins!";

        $this->assertEquals($expVal3, $gameHelper->determineWinner($playerHandValue3, $bankHandValue3));
    }
}