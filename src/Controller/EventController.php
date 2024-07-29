<?php
// src/Controller/EventController.php
namespace App\Controller;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

class EventController extends AbstractController
{
    #[Route('/api/events', name: 'get_events', methods: ['GET'])]
    #[OA\Get(
        summary: 'Get the list of all events',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Returns the list of events',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: Event::class))
                )
            )
        ]
    )]
    public function getEvents(EntityManagerInterface $entityManager): JsonResponse
    {
        $events = $entityManager->getRepository(Event::class)->findAll();
        return $this->json($events);
    }

    #[Route('/api/events/{id}', name: 'get_event', methods: ['GET'])]
    #[OA\Get(
        summary: 'Get a single event by ID',
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
                description: 'Returns the details of an event',
                content: new OA\JsonContent(ref: new Model(type: Event::class))
            )
        ]
    )]
    public function getEvent(Event $event): JsonResponse
    {
        return $this->json($event);
    }

    #[Route('/api/events', name: 'create_event', methods: ['POST'])]
    #[OA\Post(
        summary: 'Create a new event',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'name', type: 'string'),
                    new OA\Property(property: 'startDate', type: 'string', format: 'date-time'),
                    new OA\Property(property: 'endDate', type: 'string', format: 'date-time'),
                    new OA\Property(property: 'city', type: 'string'),
                    new OA\Property(property: 'price', type: 'number')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Event created',
                content: new OA\JsonContent(ref: new Model(type: Event::class))
            )
        ]
    )]
    public function createEvent(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $event = new Event();
        $event->setName($data['title']);
        $event->setStartDate(new \DateTime($data['startDate']));
        $event->setEndDate(new \DateTime($data['endDate']));
        $event->setCity("Lyon");
        $event->setPrice(0);

        $entityManager->persist($event);
        $entityManager->flush();

        return $this->json($event, 201);
    }

    #[Route('/api/events/{id}', name: 'update_event', methods: ['PUT'])]
    #[OA\Put(
        summary: 'Update an existing event',
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
                    new OA\Property(property: 'name', type: 'string'),
                    new OA\Property(property: 'startDate', type: 'string', format: 'date-time'),
                    new OA\Property(property: 'endDate', type: 'string', format: 'date-time'),
                    new OA\Property(property: 'city', type: 'string'),
                    new OA\Property(property: 'price', type: 'number')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Event updated',
                content: new OA\JsonContent(ref: new Model(type: Event::class))
            )
        ]
    )]
    public function updateEvent(Request $request, Event $event, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $event->setName($data['title']);
        $event->setStartDate(new \DateTime($data['startDate']));
        $event->setEndDate(new \DateTime($data['endDate']));

        $entityManager->flush();

        return $this->json($event);
    }

    #[Route('/api/events/{id}', name: 'delete_event', methods: ['DELETE'])]
    #[OA\Delete(
        summary: 'Delete an event',
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
                description: 'Event deleted'
            )
        ]
    )]
    public function deleteEvent(Event $event, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($event);
        $entityManager->flush();

        return new JsonResponse(null, 204);
    }
}
