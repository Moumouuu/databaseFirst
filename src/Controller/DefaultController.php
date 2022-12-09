<?php

namespace App\Controller;

use App\Entity\Series;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('default/index.html.twig', []);
    }
    #[Route('/mySeries', name: 'seriesFollowed', methods: ['GET'])]
    public function seriesFollowed(EntityManagerInterface $em): Response
    {
        $seriesFollowed = $em
            ->getRepository(Series::class)
            ->findAll();

        return $this->render('default/mySeries.html.twig', [
            'series' => $seriesFollowed,
        ]);
    }
}
