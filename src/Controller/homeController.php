<?php

namespace App\Controller;

# on va charger le Repository (manager) de Section
use App\Entity\Section;
use App\Repository\SectionRepository;
# on va utiliser l'entité de Post
use App\Entity\Post;
use App\Repository\PostRepository;
# on va charger le gestionnaire d'entité
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $em): Response
    {

        return $this->render('blog/index.html.twig', [
            'title' => 'Homepage',
            'sections' => $em->getRepository(Section::class)->findAll()
        ]);
    }

    #[Route('/section/{id}', name: 'section')]
    public function section(int $id, SectionRepository $sections): Response
    {
        # Sélection de la section quand son id vaut celui de la page
        $section = $sections->findOneBy(['id' => $id]);
        return $this->render('/section.html.twig', [
            # titre
            'title' => $section->getSectionTitle(),
            # section seule via son id
            'section' => $section,
            # toutes les sections
            'sections' => $sections->findAll(),
        ]);
    }
}