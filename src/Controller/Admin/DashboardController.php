<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\Adresse;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Transporteur;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('EcommerceAntho');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Gestion utilisateur');
        yield MenuItem::linkToCrud('user', 'fas fa-user-astronaut', User::class);
        yield MenuItem::linkToCrud('Adresse', 'fas fa-map', Adresse::class);
        yield MenuItem::section('Boutique');
      
        yield MenuItem::linkToCrud('categories', 'fas fa-list', Category::class);
        yield MenuItem::linkToCrud('produits', 'fas fa-store', Product::class);
        yield MenuItem::linkToCrud('Transporteur', 'fas fa-map', Transporteur::class);
        yield MenuItem::linkToCrud('Order', 'fas fa-shopping-cart', Order::class);
        
        // yield MenuItem::subMenu('Gestion utilisateur','fas fa-user')->setSubItems([

        //     yield MenuItem::linkToCrud('user', 'fas fa-user-astronaut', User::class),
        //     yield MenuItem::linkToCrud('Adresse', 'fas fa-map', Adresse::class),
        // ]);
        //  yield MenuItem::section('Boutique');
        //  yield MenuItem::subMenu('Boutique','fas fa-store')->setSubItems([

        //      yield MenuItem::linkToCrud('categories', 'fas fa-list', Category::class),
        //      yield MenuItem::linkToCrud('produits', 'fas fa-store', Product::class),
        //      yield MenuItem::linkToCrud('Transporteur', 'fas fa-map', Transporteur::class),
        //      yield MenuItem::linkToCrud('Order', 'fas fa-shopping-cart', Order::class),

        //  ]);
    }
}
