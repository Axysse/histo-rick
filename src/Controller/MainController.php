<?php

namespace App\Controller;

use App\Entity\EventPeriod;
use App\Entity\Events;
use App\Entity\EventTheme;
use App\Entity\EventType;
use App\Entity\Zone;
use App\Repository\EventsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\TemporalBoundariesRepository;
use App\Repository\TemporalBoundaryRepository;

final class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(EntityManagerInterface $entity): Response
    {
        $events = $entity->getRepository(Events::class)->findAll();
        $eventsType = $entity->getRepository(EventType::class)->findAll();
        $eventsPeriod = $entity->getRepository(EventPeriod::class)->findAll();
        $eventsTheme = $entity->getRepository(EventTheme::class)->findAll();
        $eventsZone = $entity->getRepository(Zone::class)->findAll();
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'events' => $events,
            'eventsType' => $eventsType,
            'eventsPeriod' => $eventsPeriod,
            'eventsTheme' => $eventsTheme,
            'eventsZone' => $eventsZone,
        ]);
    }

    #[Route('/filter-events', name: 'app_filter_events', methods: ['POST'])]
    public function filterEvents(Request $request, EventsRepository $eventsRepository, TemporalBoundaryRepository $boundariesRepository): JsonResponse
    {
        if (!$request->isXmlHttpRequest()) {
            return new Response('Accès interdit via cette URL.', Response::HTTP_FORBIDDEN);
        }

        $data = json_decode($request->getContent(), true);


        $selectedYear = $data['year'] ?? null;
        $selectedYear2 = $data['year2'] ?? null;
        $selectedType = $data['type'] ?? null;
        $selectedPeriod = $data['period'] ?? null;
        $selectedTheme = $data['theme'] ?? null;
        $selectedZone = $data['zone'] ?? null;


        $criteria = [];
        if ($selectedYear !== null && is_numeric($selectedYear) && $selectedYear2 !== null && is_numeric($selectedYear2)) {
            $criteria['year_range'] = [(int)$selectedYear, (int)$selectedYear2];
        }
        if ($selectedType !== null && $selectedType !== '') {
            $criteria['type'] = (string)$selectedType;
        }
        if ($selectedPeriod !== null && $selectedPeriod !== '') {
            $criteria['period'] = (string)$selectedPeriod;
        }
        if ($selectedTheme !== null && $selectedTheme !== '') {
            $criteria['theme'] = (string)$selectedTheme;
        }
        if ($selectedZone !== null && $selectedZone !== '') {
            $criteria['zone'] = (string)$selectedZone;
        }

        $events = $eventsRepository->findFilteredEvents($criteria);

        $eventsData = [];
        foreach ($events as $event) {
            $eventsData[] = [
                'id' => $event->getId(),
                'title' => $event->getTitle(),
                'year' => $event->getYear(),
                'shortText' => $event->getShortText(),
                'eventText' => $event->getEventText(),
                'eventPicture' => $event->getEventPicture(),
                'x' => $event->getLongitude(),
                'y' => $event->getLatitude(),
                'link' => $event->getLink(),
            ];
        }

        $routesData = [];

        if (isset($criteria['year_range'])) {
            $boundaries = $boundariesRepository->findByYear($criteria['year_range'][0]);

            foreach ($boundaries as $boundary) {
                $routesData[] = [
                    'id' => $boundary->getId(),
                    'name' => $boundary->getPoliticalEntity()?->getName() ?? 'Nom inconnu',
                    'geojson' => $boundary->getGeometry(),
                    'color' => $boundary->getPoliticalEntity()?->getColor() ?? '#3388ff',
                ];
            }
        }

        return new JsonResponse([
            'events' => $eventsData,
            'routes' => $routesData
        ]);
    }
}
