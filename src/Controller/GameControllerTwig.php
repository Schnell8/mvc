<?php

namespace App\Controller;

use App\Game\Game;
use App\Help_Functions\GameHelper;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class GameControllerTwig extends AbstractController
{
    private GameHelper $gameHelper;

    public function __construct(GameHelper $gameHelper)
    {
        $this->gameHelper = $gameHelper;
    }

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
        $playerHandValue = $this->gameHelper->calculateHandValue($playerHand);
        $bankHand = $game->getBank()->getHand();
        $bankHandValue = $this->gameHelper->calculateHandValue($bankHand);

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
        $gameResult = $this->gameHelper->determineWinner($playerHandValue, $bankHandValue);

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
        $playerHandValue = $this->gameHelper->calculateHandValue($playerHand);

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
        // hämta deck, bankhand
        $deck = $session->get('deck');
        $bankHand = $session->get('bankHand');

        // beräkna totalen för bankhand
        $bankHandValue = $this->gameHelper->calculateHandValue($bankHand);

        // är totalen under 17 dra kort och lägg i handen, uppdatera totalen
        while ($bankHandValue < 17) {
            $card = $deck->drawCard();
            $bankHand[] = $card;
            $bankHandValue = $this->gameHelper->calculateHandValue($bankHand);
        }

        // lägg i session
        $session->set('bankHand', $bankHand);
        $session->set('bankHandValue', $bankHandValue);
        $session->set('deck', $deck);

        return $this->redirectToRoute('play_game');
    }
}
