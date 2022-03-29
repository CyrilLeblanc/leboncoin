<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Dto\Post as PostDto;
use App\Entity\Image;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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

            // handle image
            // TODO handle multiple file
            /** @var \Symfony\Component\HttpFoundation\File\File $imageFile */
            if ($imageFile = $postForm->get('image')->getData()) {
                $rank = 0;
                $imageEntity = (new Image())
                    ->setPost($post)
                    ->setRank($rank);
                try {
                    $imageFile->move(
                        __DIR__ . '/../../public/img/posts/',
                        $imageEntity->getPost()->getId() . '-' . $rank
                    );
                    $entityManager->persist($imageEntity);
                } catch (FileException $e) {
                }
            }

            $entityManager->flush();
            return $this->redirectToRoute('post_view', ['idPost' => $post->getId()]);
        }

        return $this->render('post/index.html.twig', [
            'postForm' => $postForm->createView()
        ]);
    }

    #[Route('/post/view/{idPost}', name: 'post_view')]
    public function view(
        int $idPost,
        PostRepository $postRepository
    ): Response {
        return $this->render('post/view.html.twig', [
            'post' => $postRepository->find($idPost)
        ]);
    }
}
