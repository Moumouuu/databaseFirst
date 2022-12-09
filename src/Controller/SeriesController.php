<?php

namespace App\Controller;

use App\Entity\Season;
use App\Entity\Series;
use App\Form\SeriesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/series')]
class SeriesController extends AbstractController
{
    #[Route('/', name: 'series', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $seriesPerPage = 5;
        $series = $entityManager
            ->getRepository(Series::class)
            ->findBy(array(), null, $seriesPerPage, null);

        return $this->render('series/index.html.twig', [
            'series' => $series,
        ]);
    }


    #[Route('/{id}', name: 'seriesId', methods: ['GET'])]
    public function show(EntityManagerInterface $entityManager, Series $series): Response
    {
        $seasons = $entityManager
            ->getRepository(Season::class)
            ->findBy(['series' => $series->getId()], ['number' => 'asc']);

        return $this->render('series/show.html.twig', [
            'series' => $series,
            'seasons' => $seasons,
        ]);
    }

    #[Route('/add/{id}', name: 'seriesIdFollow', methods: ['GET'])]
    public function follow(Series $series, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if ($user->getSeries()->contains($series))
            $user->removeSeries($series);
        else
            $user->addSeries($series);

        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('series');
    }


    #[Route('/poster/{id}', name: 'posterId', methods: ['GET'])]
    public function showPoster(Series $series): Response
    {
        return new Response(stream_get_contents($series->getPoster()), 200, [
            'Content-type' => 'image/jpeg'
        ]);
    }
}
