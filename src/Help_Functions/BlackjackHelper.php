<?php

namespace App\Help_Functions;

class BlackjackHelper
{
    /**
     * Calculate hand value
     *
     * @param string[] $hand Array of card strings
     * @return int The total value of the hand
     */
    public function calculateDealerHandValue(array $hand): int
    {
        $handValue = 0;
        $numberOfAces = 0;
        $cardValues = [
            'king'  => 10,
            'queen'  => 10,
            'jack'  => 10,
            '10'  => 10
        ];

        foreach ($hand as $card) {
            // ta ut första delen
            $cardParts = explode('_', $card);
            $cardValue = $cardParts[0];

            if ($cardValue === 'ace') {
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
            $handValue += ($handValue + 11 <= 21) ? 11 : 1;
        }

        return $handValue;
    }

    public function calculatePlayerHandsValues(array $hands): array
    {
        // variabel för att hålla värdet för handen
        $handValues = [];

        foreach ($hands as $handIndex => $hand) {
            $handValue = 0;
            $numberOfAces = 0;
            $cardValues = [
                'king' => 10,
                'queen' => 10,
                'jack' => 10,
                '10' => 10
            ];

            foreach ($hand as $card) {

                // ta ut första delen (ex. ace i ace_of_hearts)
                $cardParts = explode('_', $card);
                $cardValue = $cardParts[0];

                // Lägg till antal ess
                if ($cardValue === 'ace') {
                    $numberOfAces++;
                }

                // Lägg till värde för kung, dam, knekt, 10
                elseif (isset($cardValues[$cardValue])) {
                    $handValue += $cardValues[$cardValue];
                }

                // Lägg till värde för övriga kort
                else {
                    $handValue += (int)$cardValue;
                }
            }

            // Lägg till värde för ess
            for ($i = 0; $i < $numberOfAces; $i++) {
                $handValue += ($handValue + 11 <= 21) ? 11 : 1;
            }

            // lägg till värde för hand
            $handValues[$handIndex] = $handValue;
        }

        return $handValues;
    }

    /**
     * Determine winning hands between dealer and player
     *
     * @param array $playerHandsValues spelarens värde för respektive hand
     * @param int $dealerHandValue dealerns värde för handen
     * @return array The result for each hand
     */
    public function calculateResults(array $playerHandsValues, int $dealerHandValue): array
    {
        // variabel för att hålla resultat
        $results = [];

        // beräkna antal händer
        $numberOfHands = count($playerHandsValues);

        // loopa spelarhänder och tilldela resultat
        for ($i = 0; $i < $numberOfHands; $i++) {
            $playerHandValue = $playerHandsValues[$i];

            // kontrollera om dealern har överstigit 21, vinn på alla händer under 21
            if ($dealerHandValue > 21) {
                $results[$i] = $playerHandValue <= 21 ? 'Win' : 'Loss';
            }

            // kontrollera om spelaren har överstigit 21
            elseif ($playerHandValue > 21) {
                $results[$i] = 'Loss';
            }

            // kontrollera om spelaren har över dealern
            elseif ($playerHandValue > $dealerHandValue) {
                $results[$i] = 'Win';
            }

            // annars förlust (lika eller om spelare under dealer)
            else {
                $results[$i] = 'Loss';
            }
        }

        return $results;
    }

    public function calculateWinnings(array $bets, array $results): int
    {
        // varibel för att hålla vinststumma
        $totalWinnings = 0;

        // beräkna antal resultat
        $numberOfResults = count($results);

        // loopa resultaten
        for ($i = 0; $i < $numberOfResults; $i++) {

            // kontrollera om resultat innehåller 'Win'
            if ($results[$i] === 'Win') {

                // vid vinst multiplicera insatsen med 2
                $totalWinnings += $bets[$i] * 2;
            }
        }

        return $totalWinnings;
    }
}
