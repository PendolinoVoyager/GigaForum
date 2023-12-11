<?php

namespace App\Controller\Admin;

use App\Entity\Board;
use App\Entity\Post;
use App\Entity\Reply;
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
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(BoardCrudController::class)
            ->generateUrl();
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('GigaBlog');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Exit admin interface', 'fa fa-home', 'app_forum');
        yield MenuItem::linkToCrud('Boards', 'fas fa-list', Board::class);
        yield MenuItem::linkToCrud('Posts', 'fas fa-list', Post::class);
        yield MenuItem::linkToCrud('Replies', 'fas fa-comment', Reply::class);
        yield MenuItem::linkToCrud('User', 'fas fa-user', User::class);


    }
}
