<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAddressController extends AbstractController
{
    /**
     * @Route("/compte/addresse", name="account_address")
     */
    public function index(): Response
    {
        //dd($this->getUser()->getAddresses()); // equivalent a app.user sur Twig
        return $this->render('account/address.html.twig');
    }

    /**
     * @Route("/compte/ajouter-une-addresse", name="account_add_address")
     */
    public function add_address(Request $request, EntityManagerInterface $entityManager): Response
    {
        //dd($this->getUser()->getAddresses()); // equivalent a app.user sur Twig
        $address = new Address;

        $form = $this->createForm(AddressType::class,$address);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()){
            $address->setUser($this->getUser());

            $entityManager->persist($address);
            $entityManager->flush();

            return $this->redirectToRoute('account_address');
        }

        return $this->render('account/add_and_edit_address.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/compte/modifier-une-addresse/{id}", name="account_edit_address")
     */
    public function edit_address(Request $request, EntityManagerInterface $entityManager, $id ): Response
    {
        //dd($this->getUser()->getAddresses()); // equivalent a app.user sur Twig

        $address = $entityManager->getRepository(Address::class)->findOneById($id);

        if (!$address or $address->getUser() != $this->getUser()){
            return $this->redirectToRoute('account-address');
        }
        $form = $this->createForm(AddressType::class,$address);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()){
            $entityManager->flush();
            return $this->redirectToRoute('account_address');
        }

        return $this->render('account/add_and_edit_address.html.twig',[
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/compte/supprimer-une-addresse/{id}", name="account_delete_address")
     */
    public function delete_address(EntityManagerInterface $entityManager, $id ): Response
    {
        $address = $entityManager->getRepository(Address::class)->findOneById($id);

        if ($address and $address->getUser() == $this->getUser()){
            $entityManager->remove($address);
            $entityManager->flush();
        }
        return $this->redirectToRoute('account_address');
    }

}