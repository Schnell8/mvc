<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyControllerTwig extends AbstractController
{
    #[Route("/", name: "home")]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route("/about", name: "about")]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    #[Route("/report", name: "report")]
    public function report(): Response
    {
        return $this->render('report.html.twig');
    }

    #[Route("/lucky", name: "lucky")]
    public function number(): Response
    {
        $number = random_int(0, 100);

        $data = [
            'number' => $number
        ];

        return $this->render('lucky.html.twig', $data);
    }

    #[Route("/api/quote", name: "api_quote")]
    public function jsonQuote(): Response
    {
        $quotes = array("You are braver than you believe, and stronger than you seem, and smarter than you think.", "Positive anything is better than negative nothing.", "It is not whether you get knocked down, it is whether you get up.");
        $number = random_int(0, 2);
        $t = time();
        $date = date("Y-m-d", $t);
        $curr_time = date("H:i:s ", $t);

        $data = [
            'dagens citat' => $quotes[$number],
            'dagens datum' => $date,
            'tidsstampel' => $curr_time
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
