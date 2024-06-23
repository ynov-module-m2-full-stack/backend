<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class RegistrationController extends AbstractController
{
    #[Route('/api/users', name: 'user.creation', methods: ['POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, serializerInterface $serializer, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user_data = $request->getContent();
        $user = $serializer->deserialize($user_data, User::class, 'json');
        // ... e.g. get the user data from a registration form
        $plaintextPassword = $user->getPassword();

        // hash the password (based on the security.yaml config for the $user class)
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);

        $entityManager->persist($user);
        $entityManager->flush();
        return $this->json([
            'user' => $user,
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/RegistrationController.php'
        ]);
    }
}