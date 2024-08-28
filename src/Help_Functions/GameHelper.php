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
        $cardValues = [
            'K' => 13, // Kung
            'Q' => 12, // Dam
            'J' => 11, // Knekt
            '1' => 10  // 10
        ];

        foreach ($hand as $card) {
            // första tecknet
            $cardValue = substr($card, 0, 1);

            if ($cardValue === 'A') {
                // Lägg till antal ess
                $numberOfAces++;
            } elseif (isset($cardValues[$cardValue])) {
                // Lägg till värde för kung, dam, knekt, 10
                $handValue += $cardValues[$cardValue];
            } else {
                // Lägg till värde för övriga kort
                $handValue += (int)$cardValue;
            }
        }

        // Lägg till värde för ess
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

        // Spelare högre än bank
        if ($playerHandValue > $bankHandValue) {
            return "Player wins!";
        }

        // Bank högre än spelare
        if ($bankHandValue > $playerHandValue) {
            return "Bank wins!";
        }

        // Samma värde
        return "It's a tie!";
    }
}