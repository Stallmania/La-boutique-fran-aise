<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountPasswordController extends AbstractController
{
    /**
     * @Route("/compte/modifier-mon-mot-de-passe", name="account_password")
     */
    public function index(Request $request, EntityManagerInterface $entityManager,UserPasswordHasherInterface $userPasswordHasher)
    {
        $notif = null;
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isSubmitted()){
            $old_pass = $form->get('OldPassword')->getData(); // password not hached !

            if ($userPasswordHasher->isPasswordValid($user,$old_pass)){
                $new_pass = $form->get('NewPassword')->getData();
                $hash = $userPasswordHasher->hashPassword($user,$new_pass);

                $user->setPassword($hash);

                $entityManager->persist($user);
                $entityManager->flush();

                $notif = "Votre mot de passe a bien été mis ajour.";
            }
            else{
                $notif = "Votre mot de passe actuel n'ai pas le bon";
            }
        }

        return $this->render('account/password.html.twig',[
            'form' => $form->createView(),
            'notif'=> $notif
    ]);
    }
}
