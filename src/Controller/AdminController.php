<?php

namespace App\Controller;

use App\Repository\SectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

// 😊😊
class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    #[isGranted('ROLE_ADMIN')]
    public function index(SectionRepository $em): Response
    {
        return $this->render('blog/index.html.twig', [
            'title' => 'Administration',
            'sections' => $em->findAll()
        ]);
    }
}

