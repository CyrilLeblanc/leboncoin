<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\FavoriteRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Favorite as FavoriteEntity;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FavoriteController extends AbstractController
{

    #[Route('/favorite', name: 'favorite_index')]
    public function index(
        FavoriteRepository $favoriteRepository
    ): Response {
        $favorites = $favoriteRepository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('favorite/index.html.twig', [
            'favorites' => $favorites,
        ]);        
    }

    #[Route('/favorite/toggle/{idPost}', name: 'favorite_toggle')]
    public function toggle(
        int $idPost,
        PostRepository $postRepository,
        FavoriteRepository $favoriteRepository,
        EntityManagerInterface $entityManager
    ): RedirectResponse {

        $post = $postRepository->findOneBy(['id' => $idPost]);
        if ($favoriteRepository->findOneBy(['post' => $post, 'user' => $this->getUser()])) {
            $entityManager->remove($favoriteRepository->findOneBy(['post' => $post, 'user' => $this->getUser()]));
        } else {
            $favorite = (new FavoriteEntity())
                ->setPost($post)
                ->setUser($this->getUser())
                ->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($favorite);
        }
        $entityManager->flush();

        return $this->redirectToRoute('post_view', ['idPost' => $idPost]);
    }
}
