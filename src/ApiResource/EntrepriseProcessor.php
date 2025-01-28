<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Entreprise;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EntrepriseProcessor implements ProcessorInterface
{
    private ProcessorInterface $decoratedProcessor;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(ProcessorInterface $decoratedProcessor, UserPasswordHasherInterface $passwordHasher)
    {
        $this->decoratedProcessor = $decoratedProcessor;
        $this->passwordHasher = $passwordHasher;
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if (in_array($operation->getMethod(), ['POST', 'PUT'], true)) {
            if ($data instanceof Entreprise && $data->getPassword()) {
                $hashedPassword = $this->passwordHasher->hashPassword($data, $data->getPassword());
                $data->setPassword($hashedPassword);
            }
        }

        return $this->decoratedProcessor->process($data, $operation, $uriVariables, $context);
    }
}
