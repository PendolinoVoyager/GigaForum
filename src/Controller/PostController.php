<?php

namespace App\Controller;

use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post_new')]
    public function new(): Response
    {
        $form = $this->createForm(PostType::class);
        return $this->render('post/new.html.twig', [
            'form' => $form,
        ]);
    }
}
