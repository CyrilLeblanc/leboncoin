<?php

namespace App\Controller;

use App\Dto\Research;
use App\Entity\Research as ResearchEntity;
use App\Form\ResearchType;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    #[Route('/', name: 'index_index')]
    public function index(
        Request $request,
        PostRepository $postRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $researchDto = new Research();
        $researchForm = $this->createForm(ResearchType::class, $researchDto);
        $researchForm->handleRequest($request);

        $posts = [];

        if ($researchForm->isSubmitted() && $researchForm->isValid()) {
            $posts = $postRepository->search(
                $researchDto->getQuery(),
                $researchDto->getCategory(),
                $researchDto->getMinCost(),
                $researchDto->getMaxCost(),
                $researchDto->getPostcode()
            );

            if ($this->getUser()) {
                $research = (new ResearchEntity())
                    ->setDateTime(new \DateTime())
                    ->setCategory($researchDto->getCategory())
                    ->setQuery($researchDto->getQuery())
                    ->setMinPrice($researchDto->getMinCost())
                    ->setMaxPrice($researchDto->getMaxCost())
                    ->setPostcode($researchDto->getPostcode())
                    ->setUser($this->getUser());
                $entityManager->persist($research);
                $entityManager->flush();
            }
        } else {
            $posts = $postRepository->search();
        }

        return $this->render('index/index.html.twig', [
            'research_form' => $researchForm->createView(),
            'posts' => $posts,
            'user' => $this->getUser()
        ]);
    }

    #[Route('/index_default', name: '')]
    public function indexRedirect(): Response
    {
        return $this->redirectToRoute('index_index');
    }
}
