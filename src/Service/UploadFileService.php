<?php

namespace App\Service;

use PhpOffice\PhpSpreadsheet\IOFactory;

class UploadFileService
{
    public function extractDataFromFile($file): array
    {
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
                    $data[$mois] = $consommation ?? 0;
                }
            }

            return $data;
     
    }
}