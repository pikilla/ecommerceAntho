<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Form\AdresseType;
use Doctrine\ORM\EntityManager;
use App\Form\ChangePasswordType;
use Symfony\Component\Mime\Address;
use App\Repository\AdresseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }
    #[Route('/account/pwd', name: 'account_pwd')]
    public function changePassword(Request $request,UserPasswordEncoderInterface $encoder): Response
    {
        $notif=null;
        $user=$this->getUser();
        $form= $this->createForm(ChangePasswordType::class,$user);
       
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $oldPwd=$form->get('password_old')->getData();
            if($encoder->isPasswordValid($user,$oldPwd)){
                $notif='votre mot de passe est bien modifié';
                $newPwd=$form->get('password_new')->getData();
                $hash=$encoder->encodePassword($user,$newPwd);
                $user->setPassword($hash);
               $entityManager=$this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                // return $this->redirectToRoute("home");
            }else{
                $notif='mdp actuel n\'est pas bon';
            }
           
           }
       return $this->render('account/changePassword.html.twig', [
           'form' =>$form->createView(),
           'notif'=>$notif
           //'editMode'=>$user->getId()!==null
       ]);
       
    }
    #[Route('/account/adresse', name: 'adresse')]
    public function adresse(): Response
    {
        return $this->render('adresse/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }
    
    #[Route('/account/adresseAdd', name: 'adresseAdd')]
    #[Route('/account/adresseAdd/{id}', name:'adresse_modification')]
     
   
    public function adresseAdd(Adresse $adresse=null,Request $request,EntityManagerInterface $entityManager): Response
    {
        if(!$adresse){
            $adresse=new Adresse();}
        
        $form=$this->createForm(AdresseType::class,$adresse);
        $form->handleRequest($request);
       
        if($form->isSubmitted()&&$form->isValid()){
            // $adresse=$form->getData();
            $user=$this->getUser();
            // dd($user);
            $adresse->setUsers($this->getUser());
           
            $entityManager=$this->getDoctrine()->getManager();
            $entityManager->persist($adresse);
            $entityManager->flush();
            // if($adresse->get()
            return $this->redirectToRoute("adresse");
        }
    return $this->render('adresse/adresseAdd.html.twig', [
        'form' =>$form->createView(),
        //'editMode'=>$user->getId()!==null
    ]);
    }
     /**
     * @Route("/account/adresseRemove/{id}", name="adresseRemove")
     */
    public function adresseRemove(Adresse $adresse): Response
    {       
       if(!$adresse){return $this->redirectToRoute("adresse");}
        $entityManager=$this->getDoctrine()->getManager();
        $entityManager->remove($adresse);
       $entityManager->flush();
      
       return $this->redirectToRoute("adresse");
       
        // return $this->render('boutique/panier.html.twig', [
        //     'produits' => $produits->findAllByCategorie($categorie),
            
            
        // ]);
    }
}
