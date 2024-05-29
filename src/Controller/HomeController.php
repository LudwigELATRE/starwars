<?php

namespace App\Controller;

use App\Service\StarWarAPIService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    private StarWarAPIService $starWarAPIService;
    private string $url = "people/";

    public function __construct(StarWarAPIService $starWarAPIService)
    {
        $this->starWarAPIService = $starWarAPIService;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $nextUrl = $this->starWarAPIService->getInfoNextUrl($this->url);
        $personnages = $this->starWarAPIService->getInformations($this->url);

        return $this->render('home/index.html.twig', [
            'personnages' => $personnages,
            'nextUrl' => $nextUrl,
        ]);
    }

    #[Route('/personnage/{id}', name: 'app_personnage', requirements: ['id' => '\d+'])]
    public function showPersonnage(int $id): Response
    {
        $personnage = $this->starWarAPIService->getElement($this->url, $id);

        return $this->render('home/personnage.html.twig', [
            'personnage' => $personnage,
        ]);

    }

    #[Route('/personnages/{page}', name: 'app_next_personnages', requirements: ['page' => '\d+'])]
    public function personnagePage(int $page): Response
    {
        $nextUrl = $this->starWarAPIService->getInfoNextUrl($this->url, $page);
        $personnages = $this->starWarAPIService->getPersonnagesWithAOtherPage($this->url, $page);
        if ($nextUrl['next'] === null || $nextUrl['previous'] === null) {
            $this->redirect($this->url.$page);
        }

        return $this->render('home/index.html.twig', [
            'personnages' => $personnages,
            'nextUrl' => $nextUrl,
        ]);
    }
}
