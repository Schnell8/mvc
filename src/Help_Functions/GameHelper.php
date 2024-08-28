<?php

namespace App\Help_Functions;

class GameHelper
{
    /**
     * Calculate hand value
     *
     * @param string[] $hand Array of card strings
     * @return int The total value of the hand
     */
    public function calculateHandValue(array $hand): int
    {
        $handValue = 0;
        $numberOfAces = 0;

        foreach ($hand as $card) {
            $cardValue = substr($card, 0, 1); // första tecknet

            switch ($cardValue) {
                case 'A':
                    $numberOfAces++;
                    break;
                case 'K':
                    $handValue += 13;
                    break;
                case 'Q':
                    $handValue += 12;
                    break;
                case 'J':
                    $handValue += 11;
                    break;
                case '1':
                    $handValue += 10;
                    break;
                default:
                    $handValue += (int)$cardValue;
                    break;
            }
        }

        // Justera värdet för ess
        for ($i = 0; $i < $numberOfAces; $i++) {
            $handValue += ($handValue + 14 <= 21) ? 14 : 1;
        }

        return $handValue;
    }

    /**
     * Determine the winner based on hand values
     *
     * @param int $playerHandValue
     * @param int $bankHandValue
     * @return string The result of the game
     */
    public function determineWinner(int $playerHandValue, int $bankHandValue): string
    {
        // Både spelare och bank över 21
        if ($playerHandValue > 21 && $bankHandValue > 21) {
            return "It's a tie!";
        }

        // Spelare över 21
        if ($playerHandValue > 21) {
            return "Bank wins!";
        }

        // Bank över 21
        if ($bankHandValue > 21) {
            return "Player wins!";
        }

        // Spelare under 21 men över bank
        if ($playerHandValue > $bankHandValue) {
            return "Player wins!";
        }

        // Spelare under 21 men under bank
        if ($playerHandValue < $bankHandValue) {
            return "Bank wins!";
        }

        return "It's a tie!";
    }
}