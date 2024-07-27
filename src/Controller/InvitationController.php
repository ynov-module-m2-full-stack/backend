<?php
// src/Controller/InvitationController.php
namespace App\Controller;

use App\Entity\Invitation;
use App\Entity\User;
use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\SecurityBundle\Security;

class InvitationController extends AbstractController
{
 function __construct(private Security $security)
    {
    }

    #[Route('/api/invitations', name: 'get_invitations', methods: ['GET'])]
    #[OA\Get(
        summary: 'Get the list of all invitations',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Returns the list of invitations',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: Invitation::class))
                )
            )
        ]
    )]
    public function getInvitations(EntityManagerInterface $entityManager): JsonResponse
    {
        $invitations = $entityManager->getRepository(Invitation::class)->findAll();
        return $this->json($invitations);
    }

    #[Route('/api/invitations/{id}', name: 'get_invitation', methods: ['GET'])]
    #[OA\Get(
        summary: 'Get a single invitation by ID',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Returns the details of an invitation',
                content: new OA\JsonContent(ref: new Model(type: Invitation::class))
            )
        ]
    )]
    public function getInvitation(Invitation $invitation): JsonResponse
    {
        return $this->json($invitation);
    }

    #[Route('/api/invitations', name: 'create_invitation', methods: ['POST'])]
    #[OA\Post(
        summary: 'Create a new invitation',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'status', type: 'string'),
                    new OA\Property(property: 'idUser', type: 'integer'),
                    new OA\Property(property: 'idEvent', type: 'integer')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Invitation created',
                content: new OA\JsonContent(ref: new Model(type: Invitation::class))
            )
        ]
    )]
    public function createInvitation(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $invitation = new Invitation();
        $invitation->setStatus($data['status']);
        $invitation->setIdUser($this->security->getUser());
        $invitation->setIdEvent($entityManager->getRepository(Event::class)->find($data['idEvent']));

        $entityManager->persist($invitation);
        $entityManager->flush();

        return $this->json($invitation, 201);
    }

    #[Route('/api/invitations/{id}', name: 'update_invitation', methods: ['PUT'])]
    #[OA\Put(
        summary: 'Update an existing invitation',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'status', type: 'string'),
                    new OA\Property(property: 'idUser', type: 'integer'),
                    new OA\Property(property: 'idEvent', type: 'integer')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Invitation updated',
                content: new OA\JsonContent(ref: new Model(type: Invitation::class))
            )
        ]
    )]
    public function updateInvitation(Request $request, Invitation $invitation, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $invitation->setStatus($data['status']);
        $invitation->setIdUser($entityManager->getRepository(User::class)->find($data['idUser']));
        $invitation->setIdEvent($entityManager->getRepository(Event::class)->find($data['idEvent']));

        $entityManager->flush();

        return $this->json($invitation);
    }

    #[Route('/api/invitations/{id}', name: 'delete_invitation', methods: ['DELETE'])]
    #[OA\Delete(
        summary: 'Delete an invitation',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Invitation deleted'
            )
        ]
    )]
    public function deleteInvitation(Invitation $invitation, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($invitation);
        $entityManager->flush();

        return new JsonResponse(null, 204);
    }
}
