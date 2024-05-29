<?php

namespace App\Controller;

use App\Service\StarWarAPIService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PlaneteController extends AbstractController
{
    private StarWarAPIService $starWarAPIService;
    private string $url = "planets/";

    public function __construct(StarWarAPIService $starWarAPIService)
    {
        $this->starWarAPIService = $starWarAPIService;
    }

    #[Route('/planete', name: 'app_planetes')]
    public function index(): Response
    {
        $nextUrl = $this->starWarAPIService->getInfoNextUrl($this->url);
        $planetes = $this->starWarAPIService->getInformations($this->url);

        return $this->render('planete/index.html.twig', [
            'planetes' => $planetes,
            'nextUrl' => $nextUrl,
        ]);
    }

    #[Route('/planete/{id}', name: 'app_planete', requirements: ['id' => '\d+'])]
    public function showPlanete(int $id): Response
    {
        $planete = $this->starWarAPIService->getElement($this->url, $id);

        return $this->render('planete/planete.html.twig', [
            'planete' => $planete,
        ]);

    }

    #[Route('/planetes/{page}', name: 'app_next_planetes', requirements: ['page' => '\d+'])]
    public function planetePage(int $page): Response
    {
        $nextUrl = $this->starWarAPIService->getInfoNextUrl($this->url, $page);
        $planetes = $this->starWarAPIService->getPersonnagesWithAOtherPage($this->url, $page);
        if ($nextUrl['next'] === null || $nextUrl['previous'] === null) {
            $this->redirect($this->url.$page);
        }

        return $this->render('planete/index.html.twig', [
            'planetes' => $planetes,
            'nextUrl' => $nextUrl,
        ]);
    }
}
