<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Form\EditFormType;
use App\Security\AppLoginAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\Security;



class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, AppLoginAuthenticator $authenticator): Response
    {
        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/{id}/edit", name="user_edit")
     */

    public function edit(Users $user,UserPasswordEncoderInterface $encoder, Request $request,Security $security, EntityManagerInterface $em, TranslatorInterface $translator)
    {
        $user = $this->getUser();
        $form = $this->createForm(EditFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Users $user */
            //dump($request->request);die();

            $user = $form->getData();
            $newPassword = $request->request->get('edit_form')['plainPassword'];
            $encodedPassword = $encoder->encodePassword($user, $newPassword);
            $user->setPassword($encodedPassword);
            $em->persist($user);
            $em->flush();
            $message= $translator->trans('Utilisateur modifier avec succes!');
            $this->addFlash('success', $message);
            return $this->redirectToRoute('app_home');
            //'Utilisateur modifier avec succes!'
        }
        return $this->render('registration/edit.html.twig', [
            'editForm' => $form->createView()
        ]);
    }
}
