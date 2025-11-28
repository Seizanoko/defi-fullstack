<?php
// src/Controller/AuthController.php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class AuthController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private ValidatorInterface $validator,
        private JWTTokenManagerInterface $jwtManager
    ) {}

    #[Route('/api/auth/register', name: 'api_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json(['message' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        // Validate required fields
        $requiredFields = ['username', 'password'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                return $this->json([
                    'message' => "Missing required field: $field"
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        // Check if user already exists
        $existingUser = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['username' => $data['username']]);

        if ($existingUser) {
            return $this->json([
                'message' => 'User already exists'
            ], Response::HTTP_CONFLICT);
        }

        // Create user
        $user = new User();
        $user->setUsername($data['username']);
        $user->setRoles(['ROLE_USER']);

        // Hash password
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $data['password']
        );
        $user->setPassword($hashedPassword);

        // Validate entity
        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json([
                'message' => 'Validation failed',
                'errors' => $errorMessages
            ], Response::HTTP_BAD_REQUEST);
        }

        // Save user
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Generate JWT token
        $token = $this->jwtManager->create($user);

        return $this->json([
            'message' => 'User registered successfully',
            'user' => [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
            ],
            'token' => $token
        ], Response::HTTP_CREATED);
    }

    #[Route('/api/auth/login', name: 'api_auth_login', methods: ['POST'])]
    public function login(): void
    {
        // This method can be empty - the security layer will intercept the request
    }
}