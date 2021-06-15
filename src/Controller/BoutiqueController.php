<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Order;
use App\Form\OrderType;
use App\Entity\OrderDetails;
use Stripe\Checkout\Session;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BoutiqueController extends AbstractController
{
  /**
     * @Route("/boutique", name="boutique")
     */
    public function Boutique(ProductRepository $produits,SessionInterface $session,CategoryRepository $catBoutRepo): Response
    {       
        $panier=$session->get('panier',[]);
        $panierInfo=[];
        foreach($panier as $id => $quantite){
            $panierInfo[]=[
            'produit'=> $produits->find($id),
            'quantite'=>$quantite,
            ];
        }
      $total=0;
      foreach($panierInfo as $item){
          $totalProduit=$item['produit']->getPrice()*$item['quantite'];
          $total+=$totalProduit;
      }
        
      $catBoutRepo=$catBoutRepo->findAll();
      $categories=[];
      foreach($catBoutRepo as $categorie){
        array_push($categories,$categorie->getNom());
        
      }
     
        return $this->render('boutique/boutique.html.twig', [
            'produits' => $produits->findAll(),
            'categories'=>$categories,
            'items'=>$panierInfo,
            'total'=>$total
        ]);
    }
    /**
     * @Route("/boutiqueDetail/{slug}", name="boutiqueDetail")
     */
    public function boutiqueDetail(ProductRepository $produits,SessionInterface $session,$slug,CategoryRepository $catBoutRepo): Response
    {       
        $panier=$session->get('panier',[]);
        $panierInfo=[];
        foreach($panier as $id => $quantite){
            $panierInfo[]=[
            'produit'=> $produits->find($id),
            'quantite'=>$quantite,
            ];
        }
      $total=0;
      foreach($panierInfo as $item){
          $totalProduit=$item['produit']->getPrice()*$item['quantite'];
          $total+=$totalProduit;
      }
     
        return $this->render('boutique/boutiqueDetail.html.twig', [
            'produit' => $produits->findOneBySlug($slug),
            'items'=>$panierInfo,
            'total'=>$total
            
        ]);
    }
    /**
     * @Route("/boutique/{categorie}", name="boutique_categorie")
     */
    public function BoutiqueCategorie(ProductRepository $produits,$categorie,SessionInterface $session): Response
    {       
        $panier=$session->get('panier',[]);
        $panierInfo=[];
        foreach($panier as $id => $quantite){
            $panierInfo[]=[
            'produit'=> $produits->find($id),
            'quantite'=>$quantite,
            ];
        }
      $total=0;
      foreach($panierInfo as $item){
          $totalProduit=$item['produit']->getPrice()*$item['quantite'];
          $total+=$totalProduit;
      }
      
      
        return $this->render('boutique/boutique_categorie.html.twig', [
            'produits' => $produits->findAllByCategorie($categorie),
            'titreCategorie'=>$categorie,
            'items'=>$panierInfo,
            'total'=>$total
            
        ]);
    }
    /**
     * @Route("/panier", name="panier")
     */
    public function Panier(SessionInterface $session,ProductRepository $produit): Response
    {       
        $panier=$session->get('panier',[]);
        $panierInfo=[];
        foreach($panier as $id => $quantite){
            $panierInfo[]=[
            'produit'=> $produit->find($id),
            'quantite'=>$quantite,
            ];
        }
      $total=0;
      foreach($panierInfo as $item){
          $totalProduit=$item['produit']->getPrice()*$item['quantite'];
          $total+=$totalProduit;
      }
      
      
        return $this->render('boutique/panier.html.twig',[
            'items'=>$panierInfo,
            'total'=>$total
        ]);
    }
    /**
     * @Route("/panier/add/{id}", name="panier_add")
     */
    public function add(SessionInterface $session,$id): Response
    {       
       
       $panier=$session->get('panier',[]);
       if(!empty($panier[$id])){
         $panier[$id]++;
       }else{
         $panier[$id]=1;
    }

       $session->set('panier',$panier);
       
       return $this->redirectToRoute("boutique");
        // return $this->render('boutique/panier.html.twig', [
        //     'produits' => $produits->findAllByCategorie($categorie),
            
            
        // ]);
    }
    /**
     * @Route("/panier/add/addCategorie/{id}", name="addCategorie")
     */
    public function addCategorie(SessionInterface $session,$id,ProductRepository $categorieRepo): Response
    {       
       $categorie=$categorieRepo->findOneById($id)->getCategory();
   
       $panier=$session->get('panier',[]);
       if(!empty($panier[$id])){
         $panier[$id]++;
       }else{
         $panier[$id]=1;
    }

       $session->set('panier',$panier);
       
       return $this->redirectToRoute("boutique_categorie",['categorie'=>$categorie,]);
        // return $this->render('boutique/panier.html.twig', [
        //     'produits' => $produits->findAllByCategorie($categorie),
            
            
        // ]);
    }
    /**
     * @Route("/panier/remove/{id}", name="panier_remove")
     */
    public function remove(SessionInterface $session,$id): Response
    {       
       
       $panier=$session->get('panier',[]);
       if(!empty($panier[$id])){
        unset($panier[$id]);
       }

       $session->set('panier',$panier);
       return $this->redirectToRoute("panier");
       
        // return $this->render('boutique/panier.html.twig', [
        //     'produits' => $produits->findAllByCategorie($categorie),
            
            
        // ]);
    }
    /**
     * @Route("/panier/removeAll", name="panier_removeAll")
     */
    public function removeAll(SessionInterface $session): Response
    {       
       
       $panier=$session->get('panier',[]);
       if(!empty($panier)){
        unset($panier);
       }

       $session->set('panier',[]);
       return $this->redirectToRoute("panier");
       
        // return $this->render('boutique/panier.html.twig', [
        //     'produits' => $produits->findAllByCategorie($categorie),
            
            
        // ]);
    }
    /**
     * @Route("/panier/moins/{id}", name="panier_moins")
     */
    public function moins(SessionInterface $session,$id): Response
    {       
       
       $panier=$session->get('panier',[]);
       
       if($panier[$id]>1){
        $panier[$id]=$panier[$id]-1;
      }else{
        unset($panier[$id]);
   }

       $session->set('panier',$panier);
       return $this->redirectToRoute("panier");
       
        // return $this->render('boutique/panier.html.twig', [
        //     'produits' => $produits->findAllByCategorie($categorie),
            
            
        // ]);
    }
    /**
     * @Route("/panier/plus/{id}", name="panier_plus")
     */
    public function plus(SessionInterface $session,$id): Response
    {       
       
       $panier=$session->get('panier',[]);
       if(!empty($panier[$id])){
        $panier[$id]++;
      }else{
        $panier[$id]=1;
   }

       $session->set('panier',$panier);
       return $this->redirectToRoute("panier");
       
        // return $this->render('boutique/panier.html.twig', [
        //     'produits' => $produits->findAllByCategorie($categorie),
            
            
        // ]);
    }
    /**
     * @Route("/panier/removeBoutique/{id}", name="panier_removeBoutique")
     */
    public function removeBoutique(SessionInterface $session,$id): Response
    {       
       
       $panier=$session->get('panier',[]);
       if(!empty($panier[$id])){
        unset($panier[$id]);
       }

       $session->set('panier',$panier);
       return $this->redirectToRoute("boutique");
       
        // return $this->render('boutique/panier.html.twig', [
        //     'produits' => $produits->findAllByCategorie($categorie),
            
            
        // ]);
    }
    /**
     * @Route("/panier/removeBoutiqueCategorie/{id}", name="panier_removeBoutiqueCategorie")
     */
    public function removeBoutiqueCategorie(SessionInterface $session,$id,ProduitRepository $categorieRepo): Response
    {       
        $categorie=$categorieRepo->findOneById($id)->getCategory();
       $panier=$session->get('panier',[]);
       if(!empty($panier[$id])){
        unset($panier[$id]);
       }

       $session->set('panier',$panier);
       return $this->redirectToRoute("boutique_categorie",['categorie'=>$categorie,]);
       
        // return $this->render('boutique/panier.html.twig', [
        //     'produits' => $produits->findAllByCategorie($categorie),
            
            
        // ]);
    }
    /**
     * @Route("/account/verifCommande/{total}", name="verifCommande")
     */
   
    public function verifCommande($total,SessionInterface $session,ProductRepository $produit): Response
    {
        $panier=$session->get('panier',[]);
        $panierInfo=[];
        foreach($panier as $id => $quantite){
            $panierInfo[]=[
            'produit'=> $produit->findOneById($id),
            'quantite'=>$quantite,
            ];
        }
      $total=0;
      foreach($panierInfo as $item){
          $totalProduit=$item['produit']->getPrice()*$item['quantite'];
          $total+=$totalProduit;
      }
      $session->set('total',$total);
        $form=$this->createForm(OrderType::class,null,['user'=>$this->getUser()]);
        return $this->render('boutique/verifCommande.html.twig',[
            'total'=>$total,
            'form'=>$form->createView(),
            'items'=>$panierInfo,
        ]
        );
    }
    /**
     * @Route("/account/paiement", name="paiement",methods="POST")
     */
//    methods:['POST','HEAD']
    public function paiement(EntityManagerInterface $entityManager,SessionInterface $session1,Request $request,ProductRepository $produit): Response
    {
        $form=$this->createForm(OrderType::class,null,['user'=>$this->getUser()]);
        $form->handleRequest($request);
         $session1->get('total');
        ;
        if($form->isSubmitted()&&$form->isValid()){
        //     $user=$form->getData();
           $date=new \DateTime();
           $deliverer=$form->get('transporteur')->getData();
           $delivery=$form->get('address')->getData();
$adressDelivery=
$delivery->getFirstName();
if($delivery->getSociete()){
    $adressDelivery.='<br/>'.
$delivery->getSociete();


}
$adressDelivery.='<br/>'.
$delivery->getPhone();
$adressDelivery.='<br/>'.
$delivery->getAddress();
$adressDelivery.='<br/>'.
$delivery->getCity();
// dd($adressDelivery);
// j'enregistre l'order
$order=new Order();
$order->setUser($this->getUser());
$order->setCreatedAt($date);
$order->setDeliverer($deliverer->getNom());
$order->setDelivererPrice($deliverer->getPrice());
$order->setDeliveryAdress($adressDelivery);
$order->setIsPaid(0);
$panier=$session1->get('panier',[]);
$panierInfo=[];
foreach($panier as $id => $quantite){
    $panierInfo[]=[
    'produit'=> $produit->findOneById($id),
    'quantite'=>$quantite,
    ];
}
$total=0;
$entityManager->persist($order);
foreach($panierInfo as $item){
  $totalProduit=$item['produit']->getPrice()*$item['quantite'];
  $total+=$totalProduit;
  $orderDetails=new OrderDetails;
  $orderDetails->setMyOrder($order);
  $orderDetails->addProduct($item['produit']);
  $orderDetails->setQuantity($item['quantite']);
  $orderDetails->setPrice($item['produit']->getPrice());
  $orderDetails->setTotal($item['produit']->getPrice()*$item['quantite']);

  $entityManager->persist($orderDetails);




}
$totalFinal=$total+$order->getDelivererPrice();
$session1->set('totalFinal',$totalFinal);

$entityManager->flush();
// dd($order->getTotal());
return $this->render('boutique/paiement.html.twig',[
    'cart'=>$panierInfo,
    'deliverer'=>$deliverer,  
    'delivery'=>$adressDelivery,  
    // 'form'=>$form->createView()
 ]
 );


        //    $entityManager=$this->getDoctrine()->getManager();
        //     $entityManager->persist($user);
        //     $entityManager->flush();
        //     return $this->redirectToRoute("home");
          }
        return $this->redirectToRoute('panier'
        );
    }
    /**
     * @Route("/create-checkout-session", name="checkout")
     */
    public function checkout(SessionInterface $session1, ProductRepository $produit)
    {       
        
    //     \Stripe\Stripe::setApiKey(

    //         'sk_test_51Ided6HGf1iXCRKtql47rZEi3rr95Ev2OK4oaZCp4pPK1i3R6sFIpnJXLOBW2mVbp2EmhzKcr3NA4E94LeBnroXn00RaW0Qy8y'
    //         );
           
    //         $session = \Stripe\Checkout\Session::create([
    //             'payment_method_types' => ['card'],
    //             'line_items' => [[
    //               'price_data' => [
    //                 'currency' => 'eur',
    //                 'product_data' => [
    //                   'name' => 'total',
    //                 ],
    //                 'unit_amount' => $session1->get('total'),
    //               ],
    //               'quantity' => 1,
    //             ]],
    //             'mode' => 'payment',
    //             'success_url' => $this->generateUrl('success',[],UrlGeneratorInterface::ABSOLUTE_URL),
    //             'cancel_url' => $this->generateUrl('error',[],UrlGeneratorInterface::ABSOLUTE_URL),
    //           ]);
             
    //           return new JsonResponse([ ['id' => $session->id] ]);
    // }
    \Stripe\Stripe::setApiKey(

      'sk_test_51Ided6HGf1iXCRKtql47rZEi3rr95Ev2OK4oaZCp4pPK1i3R6sFIpnJXLOBW2mVbp2EmhzKcr3NA4E94LeBnroXn00RaW0Qy8y'
      );
      $productStripe = [];
      $panier=$session1->get('panier',[]);
      $panierInfo=[];
      foreach($panier as $id => $quantite){
          $panierInfo[]=[
          'produit'=> $produit->findOneById($id),
          'quantite'=>$quantite,
          ];
      }
  foreach ($panierInfo as $product) {
      $productStripe[] = [
          'price_data' => [
              'currency' => 'eur',
              'unit_amount' => $product['produit']->getPrice(),
              'product_data' => [
                'name' => $product['produit']->getNom(),
                // 'images' => $product['produit']->getIllustration(),
              ],
          ],
          'quantity' => $product['quantite'],
      ];
  }
      $session = \Stripe\Checkout\Session::create([
          'payment_method_types' => ['card'],
          'line_items' => [$productStripe],
          'mode' => 'payment',
          'success_url' => $this->generateUrl('success',[],UrlGeneratorInterface::ABSOLUTE_URL),
          'cancel_url' => $this->generateUrl('error',[],UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
       
        return new JsonResponse([ ['id' => $session->id] ]);
      }
    /**
     * @Route("/account/success", name="success")
     */
   
    public function success(SessionInterface $session,EntityManagerInterface $entityManager): Response
    {
       

        return $this->render('boutique/success.html.twig'
        );
    }

    /**
     * @Route("/account/error", name="error")
     */
   
    public function error(): Response
    {
        

        return $this->render('boutique/error.html.twig'
        );
    }
}
