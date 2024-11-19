<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\ClimatiqService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController 
{
    
    #[Route('/number/{id}', name: 'number')]
    public function number(int $id, ClimatiqService $climatiqService): Response
    {
        $number = $climatiqService->getApi();

        return new Response(
            '<html>
                <body>
                    <p>
                        Random Number: '.$number.'
                    </p>
                    <p>
                        Number ID : '.$id.'
                    </p>
                    

                </body>
            </html>'
        );
    }

    #[Route('/estimation', name: 'estimation')]
    public function estimation(Request $request, ClimatiqService $climatiqService) : Response
    {
        // Récupérer les paramètres de requête avec des valeurs par défaut null
        $conso = $request->query->get('consommation', 0);
        $region = $request->query->get('region', 'US');
        $activite = $request->query->get('activity', 'electricity-supply_grid-source_residual_mix');
        $CO2 = $climatiqService->getEmissionEstimate($conso, $region, $activite);
        return new Response(
            '<html>
                <body>
                    <p>
                        Voici ta conso chef: '.$CO2.'
                    </p>
                </body>
            </html>'
        );
    }
}