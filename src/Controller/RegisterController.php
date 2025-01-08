<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterController extends AbstractController
{
    #[Route('/api/register', name: 'api_entreprise_register', methods: ['POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ): Response {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        // Créer un nouveau compte d'entreprise
        $entreprise = new Entreprise();

        $entreprise->setEmail($data['email'] ?? null);
        $entreprise->setPassword(password_hash($data['password'] ?? '', PASSWORD_BCRYPT));
        $entreprise->setName($data['Name'] ?? null);
        $entreprise->setLocation($data['Location'] ?? null);
        $entreprise->setSector($data['Sector'] ?? null);
        $entreprise->setPlan($data['Plan'] ?? 0);
        $entreprise->setCardInf($data['card_inf'] ?? null);

        $errors = $validator->validate($entreprise);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        // Sauvegarder l'entreprise dans la base de données
        $entityManager->persist($entreprise);
        $entityManager->flush();

        // Retourner une réponse JSON
        return $this->json([
            'success' => true,
            'message' => 'Entreprise enregistrée avec succès',
            'data' => [
                'id' => $entreprise->getId(),
                'email' => $entreprise->getEmail(),
                'name' => $entreprise->getName(),
            ],
        ], Response::HTTP_CREATED);
    }
}
