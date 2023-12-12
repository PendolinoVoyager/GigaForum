<?php

namespace App\Controller;

use App\Entity\Board;
use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
class PostController extends AbstractController
{
    #[Route('/board/{board}/post', name: 'app_post_new', priority: 2)]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(PostType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $post = $form->getData();
            $post->setAuthor($user);
            $em->persist($post);
            $em->flush();
            $this->addFlash('success', 'Added a new post.');
            return $this->redirectToRoute('app_show',
                ['board' => $post->getBoard()->getId(),
                'post' => $post->getId()
            ]);

        }
        return $this->render('post/new.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/post/edit/{id}', name: 'app_post_edit')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function edit(Request $request, EntityManagerInterface $em, Post $post): Response {

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $post = $form->getData();
            $em->persist($post);
            $em->flush();
            $this->addFlash('success', 'Added a new post.');
            return $this->redirectToRoute('app_show',
                ['board' => $post->getBoard()->getId(),
                    'post' => $post->getId()
                ]);

        }
        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'form' => $form
        ]);

    }
}
