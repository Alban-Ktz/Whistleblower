<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Service\UploadFileService;
use App\Service\ClimatiqService;

class FichierController extends AbstractController
{
    #[Route('/api/upload', name: 'api_fichier', methods: ['GET', 'POST'])]
    public function uploadFile(Request $request, UploadFileService $uploadFileService, ClimatiqService $climatiqService): JsonResponse
    {
        // Récupération du fichier uploadé
        $file = $request->files->get('file');

        if (!$file) {
            return new JsonResponse(['error' => 'Aucun fichier recu'], 400);
        }

        // Vérification de l'extension
        $extension = $file->getClientOriginalExtension();
        if (!in_array($extension, ['xls', 'xlsx'])) {
            return new JsonResponse(['error' => 'Format de fichier non supporté'], 400);
        }

        $consommations = $uploadFileService->extractDataFromFile($file);
        
        $emissions = [];
    
        // Traitement des émissions pour chaque mois
        foreach ($consommations as $mois => $conso) {
            $co2 = $climatiqService->getEmissionEstimate((int)$conso, 'FR', "electricity-supply_grid-source_residual_mix");
            $emissions[$mois] = round($co2, 3); // Arrondi à 3 décimales
        }
    
        return new JsonResponse([
            'region' => 'FR',
            'consommations' => $consommations,
            'emissions' => $emissions,
            'annee' => 2024
        ]);

    }
}
