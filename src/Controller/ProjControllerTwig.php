<?php

namespace App\Controller;

use App\Proj\Blackjack;
use App\Proj\Deck;

use App\Help_Functions\BlackjackHelper;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProjControllerTwig extends AbstractController
{
    private BlackjackHelper $blackjackHelper;

    public function __construct(BlackjackHelper $blackjackHelper)
    {
        $this->blackjackHelper = $blackjackHelper;
    }

    #[Route("/proj", name: "proj")]
    public function proj(): Response
    {
        return $this->render('proj/landing.html.twig');
    }

    #[Route("/proj/about", name: "proj_about")]
    public function projAbout(): Response
    {
        return $this->render('proj/about.html.twig');
    }

    #[Route("/proj/set_name", name: "set_name")]
    public function setName(): Response
    {
        return $this->render('proj/set_name.html.twig');
    }

    #[Route("/proj/set_name_form", name: "set_name_form")]
    public function setNameForm(SessionInterface $session, Request $request): Response
    {
        // hämta namn + antal händer
        $name = $request->request->get('name');

        // lägg namn + balans i session
        $session->set('name', $name);
        $session->set('balance', 1000);

        // gå till set_hands route
        return $this->redirectToRoute('set_hands');
    }

    #[Route("/proj/set_hands", name: "set_hands")]
    public function setHands(): Response
    {
        return $this->render('proj/set_hands.html.twig');
    }

    #[Route("/proj/set_hands_form", name: "set_hands_form")]
    public function setHandsForm(SessionInterface $session, Request $request): Response
    {
        // hämta antal händer
        $numberOfHands = $request->request->get('numberOfHands');

        // lägg antal händer i session
        $session->set('numberOfHands', $numberOfHands);

        // gå till proj_init route
        return $this->redirectToRoute('proj_init');
    }

    #[Route("/proj/init", name: "proj_init")]
    public function projInit(SessionInterface $session): Response
    {
        // hämta antal händer
        $numberOfHands = $session->get('numberOfHands');

        // skapa nytt spel
        $blackjack = new Blackjack();

        // hämta kortlek + spelare + dealer
        $deck = $blackjack->getDeck();
        $player = $blackjack->getPlayer();
        $dealer = $blackjack->getDealer();

        // lägg till kort i spelarhänderna
        $player->setupHands($deck, $numberOfHands);

        // hämta spelarhänder
        $playerHands = $player->getHands();

        // dra 2 kort
        $card1 = $deck->drawCard();
        $card2 =  $deck->drawCard();

        // lägg till dessa i dealerhand
        $dealer->addCard($card1);
        $dealer->addCard($card2);

        // hämta dealerhand
        $dealerHand = $dealer->getHand();

        // beräkna värde för spelarhänder + dealerhand
        $playerHandsValues = $this->blackjackHelper->calculatePlayerHandsValues($playerHands);
        $dealerHandValue = $this->blackjackHelper->calculateDealerHandValue($dealerHand);

        // lägg balans + kortlek + spelarhand inkl. värde + dealerhand inkl. värde i session
        $session->set('deck', $deck);
        $session->set('playerHands', $playerHands);
        $session->set('playerHandsValues', $playerHandsValues);
        $session->set('dealerHand', $dealerHand);
        $session->set('dealerHandValue', $dealerHandValue);
        $session->set('currentHandIndex', 0);

        // gå till set_name route
        return $this->redirectToRoute('set_bets');
    }

    #[Route("/proj/set_bets", name: "set_bets")]
    public function setBets(SessionInterface $session): Response
    {
        // hämta balans + antal händer från session
        $balance = $session->get('balance');
        $numberOfHands = $session->get('numberOfHands');

        return $this->render('proj/set_bets.html.twig', [
            'balance' => $balance,
            'numberOfHands' => $numberOfHands,
        ]);
    }

    #[Route("/proj/set_bets_form", name: "set_bets_form")]
    public function setBetsForm(SessionInterface $session, Request $request): Response
    {
        // hämta antal händer + balans från session
        $numberOfHands = $session->get('numberOfHands');
        $balance = $session->get('balance');

        // variabel som ska kunna hålla alla bets
        $bets = [];

        // for loop som hämtar bets från formulär och sparar undan
        for ($i = 1; $i <= $numberOfHands; $i++) {
            $bet = $request->request->get('bet' . $i);
            $bets[] = $bet;
        }

        // beräkna summan för bets
        $sumBets = array_sum($bets);

        // kontrollera att summan av bets inte överstiger balansen
        if ($sumBets > $balance) {
            // läs in sida på nytt
            return $this->redirectToRoute('set_bets');
        }

        // beräkna återstående balans
        $newBalance = $balance - $sumBets;

        // uppdatera session för bets + balans
        $session->set('bets', $bets);
        $session->set('balance', $newBalance);

        // gå till play route
        return $this->redirectToRoute('proj_play');
    }

    #[Route("/proj/play", name: "proj_play")]
    public function projPlay(SessionInterface $session): Response
    {
        // hämta spelarhand inkl. värde + nuvarande hand index + dealerhand
        // + antal valda händer + bets + name + balans
        $playerHands = $session->get('playerHands');
        $playerHandsValues = $session->get('playerHandsValues');
        $currentHandIndex = $session->get('currentHandIndex');
        $dealerHand = $session->get('dealerHand');
        $dealerHandValue = $session->get('dealerHandValue');
        $numberOfHands = $session->get('numberOfHands');
        $bets = $session->get('bets');
        $name = $session->get('name');
        $balance = $session->get('balance');

        // spara ner i gemensam variabel
        $data = [
            "playerHands" => $playerHands,
            "playerHandsValues" => $playerHandsValues,
            "currentHandIndex" => $currentHandIndex,
            "dealerHand" => $dealerHand,
            "dealerHandValue" => $dealerHandValue,
            'numberOfHands' => $numberOfHands,
            'bets' => $bets,
            'name' => $name,
            'balance' => $balance,
        ];

        return $this->render('proj/play.html.twig', $data);
    }

    #[Route("/proj/draw", name: "proj_draw")]
    public function projDraw(SessionInterface $session): Response
    {
        // hämta deck, spelarhand inkl. värde + nuvarande hand index
        $deck = $session->get('deck');
        $playerHands = $session->get('playerHands');
        $playerHandsValues = $session->get('playerHandsValues');
        $currentHandIndex = $session->get('currentHandIndex');

        // dra kort
        $card = $deck->drawCard();

        // lägg till kort i nuvarande spelarhand
        $playerHands[$currentHandIndex][] = $card;

        // beräkna värde för spelarhänder
        $playerHandsValues = $this->blackjackHelper->calculatePlayerHandsValues($playerHands);

        // Kontrollera om värdet för nuvarande hand överstiger 21
        if ($playerHandsValues[$currentHandIndex] > 21) {
            // uppdatera session för spelarhand + kortlek + nuvarande hand index
            $session->set('playerHands', $playerHands);
            $session->set('playerHandsValues', $playerHandsValues);
            $session->set('deck', $deck);

            // gå till stop route
            return $this->redirectToRoute('proj_stop');
        }

        // uppdatera session för spelarhand + kortlek + nuvarande hand index
        $session->set('playerHands', $playerHands);
        $session->set('playerHandsValues', $playerHandsValues);
        $session->set('deck', $deck);

        // gå till proj_play route
        return $this->redirectToRoute('proj_play');
    }

    #[Route("/proj/stop", name: "proj_stop")]
    public function projStop(SessionInterface $session): Response
    {
        // Hämta nuvarande hand index + antal händer
        $currentHandIndex = $session->get('currentHandIndex');
        $numberOfHands = $session->get('numberOfHands');

        // öka current hand index med 1
        $currentHandIndex++;

        // uppdatera session för nuvarande hand index
        $session->set('currentHandIndex', $currentHandIndex);

        // kontrollera om nuvarande hand index lika med antal händer
        if ($currentHandIndex >= $numberOfHands) {

            // hämta kortlek + dealerhand inkl. värde
            $deck = $session->get('deck');
            $dealerHand = $session->get('dealerHand');
            $dealerHandValue = $session->get('dealerHandValue');

            // måste dra kort vid värde under 17
            while ($dealerHandValue < 17) {
                $card = $deck->drawCard();
                $dealerHand[] = $card;
                $dealerHandValue = $this->blackjackHelper->calculateDealerHandValue($dealerHand);
            }

            // uppdatera session för dealerHand inkl. värde
            $session->set('dealerHand', $dealerHand);
            $session->set('dealerHandValue', $dealerHandValue);

            // gå till dealer route
            return $this->redirectToRoute('proj_dealer');
        }

        return $this->redirectToRoute('proj_play');
    }

    #[Route("/proj/dealer", name: "proj_dealer")]
    public function projDealer(SessionInterface $session): Response
    {
        // hämta spelarhand inkl. värde + nuvarande hand index + dealerhand inkl. värde
        // + antal valda händer + bets + name + balans
        $playerHands = $session->get('playerHands');
        $playerHandsValues = $session->get('playerHandsValues');
        $currentHandIndex = $session->get('currentHandIndex');
        $dealerHand = $session->get('dealerHand');
        $dealerHandValue = $session->get('dealerHandValue');
        $numberOfHands = $session->get('numberOfHands');
        $bets = $session->get('bets');
        $name = $session->get('name');
        $balance = $session->get('balance');

        // beräkna vinnande händer
        $results = $this->blackjackHelper->calculateResults($playerHandsValues, $dealerHandValue);

        // beräkna total vinst
        $winnings = $this->blackjackHelper->calculateWinnings($bets, $results, );

        // beräkna ny balans
        $newBalance = $balance + $winnings;

        // uppdatera session för balans
        $session->set('balance', $newBalance);

        // lägg till i template knappar för omspel eller avsluta spel samt FLASH för vinnande bets (border röd/grön?)

        // spara ner i gemensam variabel
        $data = [
            "playerHands" => $playerHands,
            "playerHandsValues" => $playerHandsValues,
            "currentHandIndex" => $currentHandIndex,
            "dealerHand" => $dealerHand,
            "dealerHandValue" => $dealerHandValue,
            'numberOfHands' => $numberOfHands,
            'bets' => $bets,
            'name' => $name,
            'balance' => $newBalance,
            'winnings' => $winnings,
            'results' => $results,
        ];

        return $this->render('proj/dealer.html.twig', $data);
    }

    #[Route("/proj/balance_restored", name: "proj_balance_restored")]
    public function projBalanceRestored(SessionInterface $session): Response
    {
        // töm session
        $session->clear();

        // sätt balans till 1000 igen
        $session->set('balance', 1000);

        // gå till proj route
        return $this->redirectToRoute('set_hands');
    }

    #[Route("/proj/reset", name: "proj_reset")]
    public function projReset(SessionInterface $session): Response
    {
        // töm session
        $session->clear();

        // gå till proj route
        return $this->redirectToRoute('proj');
    }
}
