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
use Nelmio\ApiDocBundle\Annotation\Model;

class RegistrationController extends AbstractController
{
    #[Route('/api/users', name: 'user.creation', methods: ['POST'])]
    #[\Nelmio\ApiDocBundle\Annotation\Post(
        summary: "Create a new user",
        parameters: [
            [
                "name" => "body",
                "description" => "User data",
                "dataType" => User::class,
                "required" => true,
                "format" => "application/json",
            ]
        ],
        statusCodes: [
            200 => "Successful creation of a new user",
            400 => "Invalid input data",
        ],
        response: [
            200 => [
                "description" => "Successful creation of a new user",
                "model" => User::class,
            ],
            400 => [
                "description" => "Invalid input data",
            ],
        ]
    )]
    public function createUser(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user_data = $request->getContent();
        $user = $serializer->deserialize($user_data, User::class, 'json');

        // Hash the password
        $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'user' => $user,
            'message' => 'User created successfully',
            'path' => 'src/Controller/RegistrationController.php'
        ]);
    }

    #[Route('/api/users/{id}', name: 'user.details', methods: ['GET'])]
    #[\Nelmio\ApiDocBundle\Annotation\Get(
        summary: "Get user details by ID",
        parameters: [
            [
                "name" => "id",
                "dataType" => "integer",
                "required" => true,
                "description" => "User ID",
            ]
        ],
        response: [
            200 => [
                "description" => "Successful retrieval of user details",
                "model" => User::class,
            ],
            404 => [
                "description" => "User not found",
            ],
        ]
    )]
    public function getUserDetails(User $user): Response
    {
        return $this->json($user);
    }

    #[Route('/api/users/{id}', name: 'user.update', methods: ['PUT'])]
    #[\Nelmio\ApiDocBundle\Annotation\Put(
        summary: "Update user details",
        parameters: [
            [
                "name" => "id",
                "dataType" => "integer",
                "required" => true,
                "description" => "User ID",
            ],
            [
                "name" => "body",
                "description" => "Updated user data",
                "dataType" => User::class,
                "required" => true,
                "format" => "application/json",
            ]
        ],
        statusCodes: [
            200 => "Successful update of user details",
            400 => "Invalid input data",
            404 => "User not found",
        ],
        response: [
            200 => [
                "description" => "Successful update of user details",
                "model" => User::class,
            ],
            400 => [
                "description" => "Invalid input data",
            ],
            404 => [
                "description" => "User not found",
            ],
        ]
    )]
    public function updateUserDetails(Request $request, User $user, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $updatedUser = $serializer->deserialize($request->getContent(), User::class, 'json');

        // Update user properties
        $user->setEmail($updatedUser->getEmail());
        $user->setRoles($updatedUser->getRoles());

        $entityManager->flush();

        return $this->json($user);
    }

    #[Route('/api/users/{id}', name: 'user.delete', methods: ['DELETE'])]
    #[\Nelmio\ApiDocBundle\Annotation\Delete(
        summary: "Delete user",
        parameters: [
            [
                "name" => "id",
                "dataType" => "integer",
                "required" => true,
                "description" => "User ID",
            ]
        ],
        statusCodes: [
            204 => "User successfully deleted",
            404 => "User not found",
        ]
    )]
    public function deleteUser(User $user, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($user);
        $entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
