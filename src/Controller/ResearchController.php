<?php

namespace App\Controller;

use App\Form\ResearchType;
use App\Repository\ResearchRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ResearchController extends AbstractController
{
    #[Route('/research', name: 'research_index')]
    public function index(
        ResearchRepository $researchRepository
    ): Response
    {
        return $this->render('research/index.html.twig', [
            'researches' => $researchRepository->findBy(['user' => $this->getUser()], ['dateTime' => 'DESC']),
            'formObject' => $this->createForm(ResearchType::class),
        ]);
    }
}
