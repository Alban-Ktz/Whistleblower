<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Service\ClimatiqService;

class ApiController extends AbstractController
{
    #[Route('/api/test', name: 'api', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return new JsonResponse(['API' => "Api is working."]);
    }

    #[Route('/estimation', name: 'estimation', methods: ['POST'])]
    public function estimation(Request $request, ClimatiqService $climatiqService) : JsonResponse
    {
        $data = $request->toArray();

        // Récupération et validation des données
        $consommations = $data['consommation'][0] ?? [];
        $region = $data['region'] ?? 'FR';
        $activite = $data['activity'] ?? 'electricity-supply_grid-source_residual_mix';
    
        if (empty($consommations) || !is_array($consommations)) {
            return new JsonResponse(['error' => 'Invalid or missing consommation data'], 400);
        }
    
        $emissions = [];
    
        // Traitement des émissions pour chaque mois
        foreach ($consommations as $mois => $conso) {
            $co2 = $climatiqService->getEmissionEstimate((int)$conso, $region, $activite);
            $emissions[$mois] = round($co2, 3); // Arrondi à 3 décimales
        }
    
        return new JsonResponse([
            'region' => $region,
            'emissions' => $emissions,
        ]);
        }
}