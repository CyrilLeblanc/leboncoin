<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile/{idUser}', name: 'profile_user')]
    public function index(
        int $idUser,
        UserRepository $userRepository
    ): Response {
        /** @var \App\Entity\User $user */
        $user = $userRepository->find($idUser);

        return $this->render('profile/index.html.twig', [
            'owner' => $this->getUser() == $user,
            'user' => $user,
            'address' => $user->getAddress(),
            'posts' => $user->getPosts()
        ]);
    }

    #[Route('/profile', name: 'profile_index')]
    public function myProfile(): RedirectResponse
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        return $this->redirectToRoute('profile_user', [
            'idUser' => $user->getId()
        ]);
    }
}
