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
use App\Service\MyService;

class RegistrationController extends AbstractController
{
    private $myService;

    public function __construct(MyService $myService)
    {
        $this->myService = $myService;
    }

    public function index(): Response
    {
        $data = $this->myService->getExpensiveData();
        return new Response('<html><body><pre>' . print_r($data, true) . '</pre></body></html>');
    }

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


        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/users', name: 'user.isAdmin', methods: ['GET'])]
    #[\Nelmio\ApiDocBundle\Annotation\Get(
        summary: "Get user role by token",

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
    public function getUserDetails(): Response
    {

        // Get the user object
        $user = $this->getUser();
        $user->setPassword("");
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
