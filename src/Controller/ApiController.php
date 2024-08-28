<?php

namespace App\Controller;

use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Repository\BookRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route("/api/deck", name: "api_deck", methods: ['GET'])]
    public function jsonApiDeck(SessionInterface $session): JsonResponse
    {
        $deck = new DeckOfCards();
        $deck = $deck->deckForJson();
        $cardsLeft = count($deck);

        $session->set("deck", $deck);
        $session->set("cards_left", $cardsLeft);

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
        $deck = $session->get("deck");

        if (!is_array($deck)) {
            throw new \Exception("You must init deck!");
        }

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

        if (!is_array($deck)) {
            throw new \Exception("You must init deck!");
        }

        $cardsLeft = count($deck);

        if ($cardsLeft === 0) {
            throw new \Exception("No more cards left to draw!");
        }

        shuffle($deck);
        $card = $deck[0];
        array_splice($deck, 0, 1);

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

        if (!is_array($deck)) {
            throw new \Exception("You must init deck!");
        }

        $cardsLeft = $session->get("cards_left");

        if ($cardsLeft === 0) {
            throw new \Exception("No more cards left to draw!");
        }

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

    #[Route("/api/game", name: "api_game", methods: ['POST', 'GET'])]
    public function jsonApiGame(SessionInterface $session): JsonResponse
    {
        // hämta totalen för spelare, bank samt resultat
        $playerHandValue = $session->get('playerHandValue');
        $bankHandValue = $session->get('bankHandValue');
        $gameResult = $session->get('gameResult');

        // spara i gemensam variabel
        $data = [
            "playerHandValue" => $playerHandValue,
            "bankHandValue" => $bankHandValue,
            "gameResult" => $gameResult,
        ];

        // json
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route('api/library/books', name: 'api_library')]
    public function jsonApiLibrary(
        BookRepository $bookRepository
    ): Response {
        $books = $bookRepository->findAll();

        $response = $this->json($books);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route('/api/library/book/{isbn}', name: 'api_library_isbn')]
    public function jsonApiBookById(
        BookRepository $bookRepository,
        int $isbn
    ): Response {
        $book = $bookRepository->findOneBy(['isbn' => $isbn]);

        if (!$book) {
            return $this->json(['error' => 'Book not found'], Response::HTTP_NOT_FOUND);
        }

        $response = $this->json($book);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
