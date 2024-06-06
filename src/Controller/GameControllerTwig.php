<?php

namespace App\Controller;

use App\Game\Game;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class GameControllerTwig extends AbstractController
{
    #[Route("/game", name: "game")]
    public function game(): Response
    {
        return $this->render('game/game.html.twig');
    }

    #[Route("/game/doc", name: "doc")]
    public function doc(): Response
    {
        return $this->render('game/doc.html.twig');
    }

    #[Route("/game/init", name: "init")]
    public function init(SessionInterface $session): Response
    {
        // töm session
        $session->clear();

        // initiera spel
        $game = new Game();
        $game->startGame();

        // spara ner kortleken, spelarhand, spelartotal, bankhand, banktotal i variabler
        $deck = $game->getDeck();
        $playerHand = $game->getPlayer()->getHand();
        $playerHandValue = $this->calculateHandValue($playerHand);
        $bankHand = $game->getBank()->getHand();
        $bankHandValue = $this->calculateHandValue($bankHand);

        // lägg i session
        $session->set('deck', $deck);
        $session->set('playerHand', $playerHand);
        $session->set('playerHandValue', $playerHandValue);
        $session->set('bankHand', $bankHand);
        $session->set('bankHandValue', $bankHandValue);

        // gå till game_play routen
        return $this->redirectToRoute('play_game');
    }

    #[Route("/game/play", name: "play_game")]
    public function playGame(SessionInterface $session): Response
    {
        // hämta spelarhand och total, kolla om det är korrekt gjort
        $playerHand = $session->get('playerHand');
        $playerHandValue = $session->get('playerHandValue');

        // hämta bankhand och total, kolla om det är korrekt gjort
        $bankHand = $session->get('bankHand');
        $bankHandValue = $session->get('bankHandValue');

        // beräkna vinnare
        $gameResult = $this->determineWinner($playerHandValue, $bankHandValue);

        // lägg resultat i session
        $session->set('gameResult', $gameResult);

        // spara ner i gemensam variabel
        $data = [
            "playerHand" => $playerHand,
            "playerHandValue" => $playerHandValue,
            "bankHandValue" => $bankHandValue,
            "bankHand" => $bankHand,
            "gameResult" => $gameResult,
        ];

        // rendera template och skicka med data
        return $this->render('game/play.html.twig', $data);
    }

    #[Route("/game/draw", name: "draw_card")]
    public function drawCard(SessionInterface $session): Response
    {
        // hämta deck, spelarhand
        $deck = $session->get('deck');
        $playerHand = $session->get('playerHand');

        // dra kort -> lägg till kort i spelarhand -> beräkna totalen
        $card = $deck->drawCard();
        $playerHand[] = $card;
        $playerHandValue = $this->calculateHandValue($playerHand);

        // lägg i session
        $session->set('playerHand', $playerHand);
        $session->set('playerHandValue', $playerHandValue);
        $session->set('deck', $deck);

        // om totalen är över 21 blir det bankens tur
        if ($playerHandValue > 21) {
            return $this->redirectToRoute('stay');
        }

        return $this->redirectToRoute('play_game');
    }

    #[Route("/game/stay", name: "stay")]
    public function stay(SessionInterface $session): Response
    {
        // hämta deck, bankhand, beräkna totalen
        $deck = $session->get('deck');
        $bankHand = $session->get('bankHand');
        $bankHandValue = $this->calculateHandValue($bankHand);

        // är totalen under 17 dra kort och lägg i handen, uppdatera totalen
        while ($bankHandValue < 17) {
            $card = $deck->drawCard();
            $bankHand[] = $card;
            $bankHandValue = $this->calculateHandValue($bankHand);
        }

        // lägg i session
        $session->set('bankHand', $bankHand);
        $session->set('bankHandValue', $bankHandValue);
        $session->set('deck', $deck);

        return $this->redirectToRoute('play_game');
    }

    // --- HJÄLPFUNKTIONER ---

    /**
     * Calculate hand value
     *
     * @param string[] $hand Array of card strings
     * @return int The total value of the hand
     */
    private function calculateHandValue(array $hand): int
    {
        $handValue = 0;
        $numberOfAces = 0;

        foreach ($hand as $card) {
            $cardValue = substr($card, 0, 1); // första tecknet
            if ($cardValue === 'A') {
                $numberOfAces++; // räkna antal ess
            } elseif (in_array($cardValue, ['K', 'Q', 'J', '1'])) {
                $handValue += ['K' => 13, 'Q' => 12, 'J' => 11, '1' => 10][$cardValue]; // lägg till värde för K/Q/J
            } else {
                $handValue += $cardValue; // lägg till värde för övriga kort
            }
        }

        // loopa genom essen, om +14 gör att totalen överstiger 21 använda +1 istället
        for ($i = 0; $i < $numberOfAces; $i++) {
            if ($handValue + 14 > 21) {
                $handValue += 1;
            } else {
                $handValue += 14;
            }
        }

        return $handValue; // returnera totalen
    }

    // funktion för att beräkna vem som vinner
    private function determineWinner(int $playerHandValue, int $bankHandValue): string
    {
        $gameResult = "";
        if ($playerHandValue > 21 && $bankHandValue > 21) {
            $gameResult = "It's a tie!";
        } elseif ($playerHandValue > 21 || ($bankHandValue <= 21 && $bankHandValue >= $playerHandValue)) {
            $gameResult = "Bank wins!";
        } elseif ($bankHandValue > 21 || $playerHandValue > $bankHandValue) {
            $gameResult = "Player wins!";
        }
        return $gameResult;
    }
}
