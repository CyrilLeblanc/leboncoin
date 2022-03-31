<?php

namespace App\Controller;

use App\Dto\Profile;
use App\Form\ProfileType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository
    ) {
    }


    #[Route('/profile', name: 'profile_index')]
    public function index(): RedirectResponse
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        return $this->redirectToRoute('profile_user', [
            'idUser' => $user->getId()
        ]);
    }

    #[Route('/profile/edit', name: 'profile_edit')]
    public function edit(
        Request $request,
        UserPasswordHasherInterface $hasher
    ): Response {
        $profile = new Profile($this->getUser());
        $form = $this->createForm(ProfileType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            /** @var \App\Entity\User $user */
            $user = $this->getUser();

            if ($hasher->isPasswordValid($user, $profile->getCurrentPassword())) {
                if ($profile->getNewPassword()) {
                    $user->setPassword($hasher->hashPassword($user, $profile->getNewPassword()));
                }
            } else {
                $form->addError(new \Symfony\Component\Form\FormError('Wrong current password'));
            }

            if ($form->isValid()) {
                /** @var \App\Entity\User $user */
                $user = $this->getUser();
                $user->setUsername($profile->getUsername())
                    ->setEmail($profile->getEmail())
                    ->setPhone($profile->getPhone());

                $address = $user->getAddress();
                $address
                    ->setCity($profile->getCity())
                    ->setPostcode($profile->getPostcode());
                $this->entityManager->persist($user);
                $this->entityManager->persist($address);
                $this->entityManager->flush();
                return $this->redirectToRoute('profile_index');
            }

        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/profile/{idUser}', name: 'profile_user')]
    public function user(
        int $idUser
    ): Response {
        /** @var \App\Entity\User $user */
        $user = $this->userRepository->find($idUser);

        return $this->render('profile/index.html.twig', [
            'current_user' => $this->getUser(),
            'user' => $user,
            'address' => $user->getAddress(),
            'posts' => $user->getPosts()
        ]);
    }
}
