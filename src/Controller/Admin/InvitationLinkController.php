<?php

namespace App\Controller\Admin;

use App\Entity\Invitation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class InvitationLinkController extends AbstractController
{
    #[Route('/admin/invitation-link', name: 'admin_invitation_link')]
    public function index(EntityManagerInterface $entityManager, UrlGeneratorInterface $router): Response
    {
        $invitation = new Invitation();
        $token = bin2hex(random_bytes(20));
        $invitation->setToken($token);

        $entityManager->persist($invitation);
        $entityManager->flush();

        $invitationLink = $router->generate('app_register', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

        return $this->render('admin/invitation_link.html.twig', [
            'invitationLink' => $invitationLink,
        ]);
    }
}
