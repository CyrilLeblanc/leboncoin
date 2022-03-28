<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Address;
use App\Dto\Registration;
use App\Form\RegistrationFormType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'register_index')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $registration = new Registration();
        $form = $this->createForm(RegistrationFormType::class, $registration);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            // handle password equality
            if ($form->get('password')->getData() !== $form->get('confirmPassword')->getData()) {
                $form->get('confirmPassword')->addError(new FormError('The password and its confirmation do not match'));
            }

            if ($form->isValid()) {
                $address = (new Address())
                    ->setPostcode($registration->getPostcode())
                    ->setNumber($registration->getNumber())
                    ->setCity($registration->getCity())
                    ->setStreet($registration->getStreet());
                $entityManager->persist($address);

                $user = (new User());
                $user->setEmail($registration->getEmail())
                    ->setPassword(
                        $userPasswordHasher->hashPassword(
                            $user,
                            $form->get('password')->getData()
                        )
                    )
                    ->setAddress($address);

                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('login_index');
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
