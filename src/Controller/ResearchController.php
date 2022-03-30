<?php

namespace App\Controller;

use App\Form\ResearchType;
use App\Repository\ResearchRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

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

    #[Route('/research/clear', name: 'research_clear')]
    public function clear(
        ResearchRepository $researchRepository
    ): RedirectResponse
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if ($user) {
            $history = $user->getResearch();
            foreach ($history as $research) {
                $researchRepository->remove($research);
            }
        }
        return $this->redirectToRoute('research_index');
    }
}
