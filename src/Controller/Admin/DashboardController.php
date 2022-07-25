<?php

declare(strict_types = 1);

namespace App\Controller\Admin;

use App\Entity\Amenity;
use App\Entity\Asset;
use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\Reaction;
use App\Entity\Tender;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Option 1. You can make your dashboard redirect to some common page of your backend
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()->setTitle('Chat');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Conversation', 'fas fa-inbox', Conversation::class);
        yield MenuItem::linkToCrud('Message', 'fas fa-comment', Message::class);
        yield MenuItem::linkToCrud('User', 'fas fa-users', User::class);
        yield MenuItem::linkToCrud('Reactions', 'fas fa-thumbs-up', Reaction::class);
        yield MenuItem::linkToCrud('Asset', 'fas fa-list', Asset::class);
        yield MenuItem::linkToCrud('Tender', 'fas fa-list', Tender::class);
        yield MenuItem::linkToCrud('Amenity', 'fas fa-tag', Amenity::class);
    }
}
