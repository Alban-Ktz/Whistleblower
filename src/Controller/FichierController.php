<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class FichierController extends AbstractController
{
    #[Route('/api/upload', name: 'api_fichier', methods: ['GET', 'POST'])]
    public function uploadFile(Request $request): JsonResponse
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

        try {
            // Charger le fichier Excel
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();

            // Analyser les données
            $data = [];
            $annee = 2024; 
            foreach ($sheet->getRowIterator(3) as $row) { // Commencer à la 3ème ligne
                $mois = $sheet->getCell('A' . $row->getRowIndex())->getValue();
                $consommation = $sheet->getCell('B' . $row->getRowIndex())->getValue();

                if (!empty($mois)) {
                    $data[] = [
                        'mois' => $mois,
                        'consommation' => $consommation ?? 0, // Valeur par défaut si vide
                    ];
                }
            }

            // Structurer le JSON
            $response = [
                'annee' => $annee,
                'data' => $data,
            ];

            return new JsonResponse($response);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}
