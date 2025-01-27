<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Entreprise;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Response;



class AuthentificationController extends AbstractController
{

    public function __construct(JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(#[CurrentUser] ?Entreprise $user): JsonResponse
    {

        if (null === $user) {
            return $this->json(['message' => 'missing credentials'], Response::HTTP_UNAUTHORIZED);
        }

        $token = $this->jwtManager->create($user);

        return $this->json([
            'user'  => $user->getUserIdentifier(),
            'token' => $token,
        ]);
    }
}