<?php

namespace App\Controller;

use App\Dto\Research;
use App\Form\ResearchType;
use Symfony\Component\HttpFoundation\Request;
use App\Helper\Categories as CategoriesHelper;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    #[Route('/', name: 'index_index')]
    public function index(
        Request $request,
        PostRepository $postRepository
    ): Response {
        $researchDto = new Research();
        $researchForm = $this->createForm(ResearchType::class, $researchDto);
        $researchForm->handleRequest($request);

        $posts = null;
        if ($researchForm->isSubmitted() && $researchForm->isValid()) {
            
        } else {
            $posts = $postRepository->findAll();
        }

        return $this->render('index/index.html.twig', [
            'research_form' => $researchForm->createView(),
            'posts' => $posts
        ]);
    }

    #[Route('/index_default', name: '')]
    public function indexRedirect(): Response
    {
        return $this->redirectToRoute('index_index');
    }
}
