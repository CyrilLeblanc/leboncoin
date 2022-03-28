<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Dto\Post as PostDto;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    #[Route('/post', name: 'post_index')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $postDto = new PostDto();
        $postForm = $this->createForm(PostType::class, $postDto);
        $postForm->handleRequest($request);
        if ($postForm->isSubmitted() && $postForm->isValid()) {
            $post = (new Post())
                ->setTitle($postDto->getTitle())
                ->setCategory($postDto->getCategory())
                ->setPrice($postDto->getPrice())
                ->setDetail($postDto->getDetail())
                ->setPublicationDate(new \DateTime())
                ->setUser($this->getUser());
            $entityManager->persist($post);
            $entityManager->flush();
        }

        return $this->render('post/index.html.twig', [
            'postForm' => $postForm->createView()
        ]);
    }

    #[Route('/post/{id}', name: 'post_show')]
    public function view(
        int $id,
        PostRepository $postRepository
    ): Response {
        return $this->render('post/view.html.twig', [
            'post' => $postRepository->find($id)
        ]);
    }
}
