<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    
      /**
     * @Route("/register", name="register")
     * @Route("/register/{id}", name="_modification")
     */
    public function inscription(User $user=null,Request $request,UserPasswordEncoderInterface $encoder): Response
    {  if(!$user){
        $user=new User();}
        $form= $this->createForm(UserType::class,$user);

         $form->handleRequest($request);
         if($form->isSubmitted()&&$form->isValid()){
             $user=$form->getData();
             $hash=$encoder->encodePassword($user,$user->getPassword());
             $user->setPassword($hash);
            $entityManager=$this->getDoctrine()->getManager();
             $entityManager->persist($user);
             $entityManager->flush();
             return $this->redirectToRoute("home");
            }
        return $this->render('register/index.html.twig', [
            'form' =>$form->createView(),
            //'editMode'=>$user->getId()!==null
        ]);
    }

}
