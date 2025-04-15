<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class SecurityController extends AbstractController{

    private $passwordEncoder;
    private $jwtManager;
    private $userRepository;

    public function __construct(UserPasswordHasherInterface $passwordEncoder, JWTTokenManagerInterface $jwtManager, UserRepository $userRepository)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->jwtManager = $jwtManager;
        $this->userRepository = $userRepository;
    }

    
    #[Route('/login', name: 'app_login', methods: ["POST"])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['username']) || !isset($data['password'])) {
            return $this->json(['message' => 'Missing username or password'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $user = $this->userRepository->findOneBy(['username' => $data['username']]);

        if (!$user || !$this->passwordEncoder->isPasswordValid($user, $data['password'])) {
            return $this->json(['message' => 'Invalid credentials'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $token = $this->jwtManager->create($user);

        return $this->json(['token' => $token]);
    }
}
