<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class EventController extends AbstractController
{
    #[Route('/event', name: 'app_event')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/EventController.php',
        ]);
    }

    #[Route('/api/event', name: 'event.get', methods: ['GET'])]
    public function getEvents(EventRepository $eventRepository, SerializerInterface $serializer): Response
    {
        $events = $eventRepository->findAll();
        $events = $serializer->serialize($events, 'json');
        return new Response($events);
    }
}
