<?php
namespace App\Controller\Admin;

use App\Entity\EventPeriod;
use App\Entity\Events;
use App\Entity\EventTheme;
use App\Entity\EventType;
use App\Entity\PoliticalEntity;
use App\Entity\TemporalBoundary;
use App\Entity\User;
use App\Entity\Zone;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Routing\Annotation\Route;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    private $entityManager;

        public function __construct(
        private AdminUrlGenerator $adminUrlGenerator, EntityManagerInterface $entityManager
    )
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $getUser = $this->getUser();
        if($getUser == null){
            return $this->redirectToRoute('app_main');
        };

        $eventRepository = $this->entityManager->getRepository(Events::class);
        $totalEvents = $eventRepository->count([]);

        $lastEvent = $eventRepository->findLast();

        // var_dump($lastEvent);

        return $this->render('admin/index.html.twig', [
            'totalEvents' => $totalEvents,
            'lastEvent' => $lastEvent,
        ]);

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // 1.1) If you have enabled the "pretty URLs" feature:
        // return $this->redirectToRoute('admin_user_index');
        //
        // 1.2) Same example but using the "ugly URLs" that were used in previous EasyAdmin versions:
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirectToRoute('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Tempus Mundi');
    }

    public function configureMenuItems(): iterable
    {
        return [
            yield MenuItem::linkToRoute('Générer un lien', 'fa fa-link', 'admin_invitation_link')
            ->setPermission('ROLE_ADMIN'),
            yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
            yield MenuItem::linkToCrud('User', 'fas fa-user', User::class)
            ->setPermission('ROLE_ADMIN'),
            yield MenuItem::linkToCrud('Event_type', 'fas fa-list', EventType::class)
            ->setPermission('ROLE_ADMIN'),
            yield MenuItem::linkToCrud('Event_theme', 'fas fa-list', EventTheme::class)
            ->setPermission('ROLE_ADMIN'),
            yield MenuItem::linkToCrud('Event_period', 'fas fa-calendar', EventPeriod::class)
            ->setPermission('ROLE_ADMIN'),
            yield MenuItem::linkToCrud('Events', 'fas fa-pencil', Events::class),
            yield MenuItem::linkToCrud('Zones', 'fas fa-marker', Zone::class)
            ->setPermission('ROLE_ADMIN'),
            yield MenuItem::linkToCrud('Entités politiques', 'fas fa-globe', PoliticalEntity::class)
            ->setPermission('ROLE_ADMIN'),
            yield MenuItem::linkToCrud('Frontières des entités', 'fas fa-clock', TemporalBoundary::class)
            ->setPermission('ROLE_ADMIN')
            ->setPermission('ROLE_MAPPER')
        ];
    }
}
