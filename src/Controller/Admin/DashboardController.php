<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\City;
use App\Entity\Comment;
use App\Entity\Country;
use App\Entity\District;
use App\Entity\Island;
use App\Entity\Menu;
use App\Entity\Page;
use App\Entity\Place;
use App\Entity\Tag;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->redirectToRoute('admin_category_index');

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // 1.1) If you have enabled the "pretty URLs" feature:
        // return $this->redirectToRoute('admin_user_index');
        //
        // 1.2) Same example but using the "ugly URLs" that were used in previous EasyAdmin versions:
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirectToRoute('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Onde');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('CMS');
        yield MenuItem::linkToCrud('Menu', 'fas fa-bars', Menu::class);
        yield MenuItem::linkToCrud('Categories', 'fas fa-list', Category::class);
        yield MenuItem::linkToCrud('Tags', 'fas fa-tags', Tag::class);
        yield MenuItem::linkToCrud('Articles', 'fas fa-newspaper', Article::class);
        yield MenuItem::linkToCrud('Commentaires', 'fas fa-message', Comment::class);
        yield MenuItem::section('Localisation');
        yield MenuItem::linkToCrud('Lieux', 'fas fa-map-pin', Place::class);
        yield MenuItem::linkToCrud('Zones', 'fas fa-location-dot', District::class);
        yield MenuItem::linkToCrud('Villes', 'fas fa-map-location-dot', City::class);
        yield MenuItem::linkToCrud('Iles', 'fas fa-mountain-sun', Island::class);
        yield MenuItem::linkToCrud('Pays', 'fas fa-earth-americas', Country::class);
        yield MenuItem::section('Site');
        yield MenuItem::linkToCrud('Pages', 'fas fa-file', Page::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);
    }
}
