<?php

namespace App\Controller;

use App\Entity\Events;
use App\Repository\EventsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(EntityManagerInterface $entity): Response
    {
        $events = $entity->getRepository(Events::class)->findAll();
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'events' => $events
        ]);
    }

    #[Route('/filter-events-by-year', name: 'app_filter_by_year', methods: ['POST'])]
    public function filterByYear(Request $request, EventsRepository $eventsRepository): JsonResponse // <-- Changer le type de retour
    {
        if (!$request->isXmlHttpRequest()) {
            // Pour les requêtes non AJAX, renvoyons une erreur plus explicite ou redirigeons
            return new Response('Accès interdit via cette URL.', Response::HTTP_FORBIDDEN);
        }

        $data = json_decode($request->getContent(), true);
        $selectedYear = $data['year'] ?? null;

        if (!$selectedYear || !is_numeric($selectedYear) || $selectedYear < 0 || $selectedYear > 3000) {
            // Pour les requêtes AJAX, renvoyez un JSON d'erreur avec un statut approprié
            return new JsonResponse(['error' => 'Année invalide'], Response::HTTP_BAD_REQUEST);
        }

        $yearAsInt = (int)$selectedYear;
        $events = $eventsRepository->findByYear($yearAsInt);

        // Préparer les données pour la réponse JSON
        $eventsData = [];
        foreach ($events as $event) {
            $eventsData[] = [
                'id' => $event->getId(),
                'title' => $event->getTitle(),
                'year' => $event->getYear(),
                'shortText' => $event->getShortText(),
                'eventText' => $event->getEventText(),
                'eventPicture' => $event->getEventPicture(),
                'x' => $event->getLongitude(), // Assurez-vous que x correspond bien à longitude
                'y' => $event->getLatitude(),  // Assurez-vous que y correspond bien à latitude
                // Ajoutez ici toutes les autres propriétés dont vous avez besoin en JavaScript
            ];
        }

        return new JsonResponse($eventsData); // <-- Renvoie un tableau d'objets JSON
    }
}
