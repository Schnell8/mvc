<?php

namespace App\Controller;

use App\Card\CardHand;
use App\Card\DeckOfCards;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route("/api/deck", name: "api_deck", methods: ['GET'])]
    public function jsonApiDeck(SessionInterface $session): JsonResponse
    {
        $deck = new DeckOfCards();
        $deck = $deck->deckForJson();

        $session->set("deck", $deck);

        $data = [
            "deck" => $deck,
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/shuffle", name: "api_deck_shuffle", methods: ['POST'])]
    public function jsonApiDeckShuffle(SessionInterface $session): JsonResponse
    {
        $deck = new DeckOfCards();
        $deck = $deck->deckForJson();
        shuffle($deck);

        $session->set("deck", $deck);

        $data = [
            "deck" => $deck,
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/draw", name: "api_deck_draw", methods: ['POST', 'GET'])]
    public function jsonApiDeckDraw(SessionInterface $session): JsonResponse
    {
        $deck = $session->get("deck");
        $cardsLeft = count($deck);

        if ($cardsLeft === 0) {
            throw new \Exception("No more cards left to draw!");
        } else {
            shuffle($deck);
            $card = $deck[0];
            array_splice($deck, 0, 1);
        }
        $cardsLeft = count($deck);

        $session->set("deck", $deck);
        $session->set("cards_left", $cardsLeft);

        $data = [
            "card" => $card,
            "cards_left" => $cardsLeft,
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/draw/{num<\d+>}", name: "api_deck_draw_number", methods: ['POST', 'GET'])]
    public function jsonApiDeckDrawNumber(int $num, SessionInterface $session): JsonResponse
    {
        $deck = $session->get("deck");
        $cardsLeft = $session->get("cards_left");

        if ($num > $cardsLeft) {
            throw new \Exception("Number too high!");
        }

        $hand = new CardHand();
        for ($i = 1; $i <= $num; $i++) {
            shuffle($deck);
            $card = $deck[0];
            $hand->addCard($card);
            array_splice($deck, 0, 1);
        }
        $hand = $hand->getHand();
        $cardsLeft = count($deck);

        $session->set("deck", $deck);
        $session->set("cards_left", $cardsLeft);

        $data = [
            "card_hand" => $hand,
            "cards_left" => $cardsLeft,
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

}
