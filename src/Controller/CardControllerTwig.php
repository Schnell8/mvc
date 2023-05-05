<?php

namespace App\Controller;

use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CardControllerTwig extends AbstractController
{
    #[Route("/card", name: "card")]
    public function card(): Response
    {
        return $this->render('card/card.html.twig');
    }

    #[Route("/card/deck", name: "deck")]
    public function cardDeck(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        for ($i = 1; $i <= 52; $i++) {
            $card = new CardGraphic();
            $cardString = $card->getSingleRepresentation($i);
            $deck->addCard($cardString);
        }
        $deckOfCards = $deck->getDeck();
        $numberOfCards = $deck->getNumberCards();

        $session->set("deck_of_cards", $deckOfCards);
        $session->set("cards_left", $numberOfCards);

        $data = [
            "deck_of_cards" => $deckOfCards,
        ];

        return $this->render('card/card_deck.html.twig', $data);
    }

    #[Route("/card/deck/shuffle", name: "shuffle")]
    public function cardDeckShuffle(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        for ($i = 1; $i <= 52; $i++) {
            $card = new CardGraphic();
            $cardString = $card->getSingleRepresentation($i);
            $deck->addCard($cardString);
        }
        $deckOfCards = $deck->getDeck();
        $numberOfCards = $deck->getNumberCards();
        shuffle($deckOfCards);

        $session->set("deck_of_cards", $deckOfCards);
        $session->set("cards_left", $numberOfCards);

        $data = [
            "shuffled_deck" => $deckOfCards,
        ];

        return $this->render('card/card_deck_shuffle.html.twig', $data);
    }

    #[Route("/card/deck/draw", name: "draw")]
    public function cardDeckDraw(SessionInterface $session): Response
    {
        $deckOfCards = $session->get("deck_of_cards");
        $cardsLeft = $session->get("cards_left");

        if ($cardsLeft === 0) {
            throw new \Exception("No more cards left to draw!");
        } else {
            shuffle($deckOfCards);
            $card = $deckOfCards[0];
            array_splice($deckOfCards, 0, 1);
        }
        $cardsLeft = count($deckOfCards);

        $session->set("deck_of_cards", $deckOfCards);
        $session->set("cards_left", $cardsLeft);

        $data = [
            "card" => $card,
            "cards_left" => $cardsLeft,
        ];

        return $this->render('card/card_deck_draw.html.twig', $data);
    }

    #[Route("/card/deck/draw/{num<\d+>}", name: "number")]
    public function cardDeckDrawNumber(int $num, SessionInterface $session): Response
    {
        $deckOfCards = $session->get("deck_of_cards");
        $cardsLeft = $session->get("cards_left");

        if ($num > $cardsLeft) {
            throw new \Exception("Number too high!");
        }

        $hand = new CardHand();
        for ($i = 1; $i <= $num; $i++) {
            shuffle($deckOfCards);
            $card = $deckOfCards[0];
            $hand->addCard($card);
            array_splice($deckOfCards, 0, 1);
        }
        $hand = $hand->getHand();
        $cardsLeft = count($deckOfCards);

        $session->set("deck_of_cards", $deckOfCards);
        $session->set("cards_left", $cardsLeft);

        $data = [
            "card_hand" => $hand,
            "cards_left" => $cardsLeft,
        ];

        return $this->render('card/card_deck_draw_number.html.twig', $data);
    }

    #[Route("/api", name: "api")]
    public function api(): Response
    {
        return $this->render('api.html.twig');
    }

}
