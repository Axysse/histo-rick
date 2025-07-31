<?php

namespace App\Controller;

use App\Entity\EventPeriod;
use App\Entity\Events;
use App\Entity\EventTheme;
use App\Entity\EventType;
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
        $eventsType = $entity->getRepository(EventType::class)->findAll();
        $eventsPeriod = $entity->getRepository(EventPeriod::class)->findAll();
        $eventsTheme = $entity->getRepository(EventTheme::class)->findAll();
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'events' => $events,
            'eventsType' => $eventsType,
            'eventsPeriod' => $eventsPeriod,
            'eventsTheme' => $eventsTheme,
        ]);
    }

    #[Route('/filter-events-by-year', name: 'app_filter_by_year', methods: ['POST'])]
    public function filterByYear(Request $request, EventsRepository $eventsRepository): JsonResponse
    {
        if (!$request->isXmlHttpRequest()) {
            return new Response('Accès interdit via cette URL.', Response::HTTP_FORBIDDEN);
        }

        $data = json_decode($request->getContent(), true);
        $selectedYear = $data['year'] ?? null;
        $selectedYear2 = $data['year2'] ?? null;

        if (!$selectedYear || !is_numeric($selectedYear) || $selectedYear < -2000 || $selectedYear > 3000) {
            return new JsonResponse(['error' => 'Année invalide'], Response::HTTP_BAD_REQUEST);
        }

        $yearAsInt = (int)$selectedYear;
        $yearAsInt2 = (int)$selectedYear2;
        $events = $eventsRepository->findByYear($yearAsInt, $yearAsInt2);

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
                'eventType' => $event->getEventType(),
            ];
        }

        return new JsonResponse($eventsData);
    }

        #[Route('/filter-events-by-type', name: 'app_filter_by_type', methods: ['POST'])]
    public function filterByType(Request $request, EventsRepository $eventsRepository): JsonResponse
    {
        if (!$request->isXmlHttpRequest()) {
            return new Response('Accès interdit via cette URL.', Response::HTTP_FORBIDDEN);
        }

        $data = json_decode($request->getContent(), true);
        $selectedType = $data['type'] ?? null;

        if (!$selectedType) {
            return new JsonResponse(['error' => 'Type invalide'], Response::HTTP_BAD_REQUEST);
        }

        $type = (string)$selectedType;
        $events = $eventsRepository->findByType($type);

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
                'eventType' => $event->getEventType(),
            ];
        }

        return new JsonResponse($eventsData);
    }

        #[Route('/filter-events-by-period', name: 'app_filter_by_period', methods: ['POST'])]
    public function filterByPeriod(Request $request, EventsRepository $eventsRepository): JsonResponse
    {
        if (!$request->isXmlHttpRequest()) {
            return new Response('Accès interdit via cette URL.', Response::HTTP_FORBIDDEN);
        }

        $data = json_decode($request->getContent(), true);
        $selectedPeriod = $data['period'] ?? null;

        if (!$selectedPeriod) {
            return new JsonResponse(['error' => 'Période invalide'], Response::HTTP_BAD_REQUEST);
        }

        $period = (string)$selectedPeriod;
        $events = $eventsRepository->findByPeriod($period);

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
                'eventType' => $event->getEventType(),
            ];
        }

        return new JsonResponse($eventsData);
    }

        #[Route('/filter-events-by-theme', name: 'app_filter_by_theme', methods: ['POST'])]
    public function filterByTheme(Request $request, EventsRepository $eventsRepository): JsonResponse
    {
        if (!$request->isXmlHttpRequest()) {
            return new Response('Accès interdit via cette URL.', Response::HTTP_FORBIDDEN);
        }

        $data = json_decode($request->getContent(), true);
        $selectedTheme = $data['theme'] ?? null;

        if (!$selectedTheme) {
            return new JsonResponse(['error' => 'Thème invalide'], Response::HTTP_BAD_REQUEST);
        }

        $theme = (string)$selectedTheme;
        $events = $eventsRepository->findByTheme($theme);

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
                'eventType' => $event->getEventType(),
            ];
        }

        return new JsonResponse($eventsData);
    }

     #[Route('/filter-events', name: 'app_filter_events', methods: ['POST'])]
    public function filterEvents(Request $request, EventsRepository $eventsRepository): JsonResponse
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

        return new JsonResponse($eventsData);
    }
}
