<?php

namespace App\Controller;

use App\Entity\Board;
use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use App\Repository\PostRepository;
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
            $post = $form->getData();
            $user = $this->getUser();
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
    #[IsGranted('POST_EDIT', 'post')]
    public function edit(Request $request, EntityManagerInterface $em, Post $post): Response {

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
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

    #[Route('/post/remove/{id}', name: 'app_post_remove')]
    #[IsGranted('POST_EDIT', 'post')]
    public function remove(Request $request, EntityManagerInterface $em, Post $post): Response {
        $em->remove($post);
        $em->flush();
        $this->addFlash('success', 'Successfully removed the post');
        return $this->redirectToRoute('app_forum');
    }
    #[Route('/post/like/{id}', 'app_like')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function like(Request $request, EntityManagerInterface $entityManager, Post $post): Response {
    /**
     * @var User $user
     */
    $user = $this->getUser();
    if (!$user->getLikedPosts()->contains($post)){
        $post->addLike($user);
        if ($user->getDislikedPosts()->contains($post)) {
            $post->removeDislike($user);
        }
    }
    else {
        $post->removeLike($user);
    }
    $entityManager->persist($post);
    $entityManager->flush();
    $referer = $request->headers->get('referer');
    return $this->redirect($referer);
    }

    #[Route('/post/dislike/{id}', 'app_dislike')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function dislike(Request $request, EntityManagerInterface $entityManager, Post $post): Response {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        if (!$user->getDislikedPosts()->contains($post)){
            $post->addDislike($user);
            if ($user->getLikedPosts()->contains($post)) {
                $post->removeLike($user);
            }
        }
        else {
            $post->removeDislike($user);
        }
        $entityManager->persist($post);
        $entityManager->flush();
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }
}

