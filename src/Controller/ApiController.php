<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends AbstractController
{
    #[Route('/api/test', name: 'api')]
    public function index(): JsonResponse
    {
        return new JsonResponse(['API' => "Api is working."]);
    }

    #[Route('/api/estimation', name: 'api2')]
    public function estiation(): JsonResponse
    {
        return new JsonResponse(['API' => "Api is working."]);
    }
}