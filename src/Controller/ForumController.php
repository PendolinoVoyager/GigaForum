<?php

namespace App\Controller;

use App\Entity\Board;
use App\Entity\Post;
use App\Entity\Reply;
use App\Form\ReplyType;
use App\Repository\BoardRepository;
use App\Repository\PostRepository;
use App\Repository\ReplyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{
    #[Route('/', name: 'app_forum')]
    public function index(BoardRepository $boards): Response
    {
        return $this->render('forum/index.html.twig', [
            'boards' => $boards->findAll()
        ]);
    }
    #[Route('/board/{board}', name: 'app_board')]
    public function board(Board $board, PostRepository $postRepository): Response
    {
        return $this->render('forum/board.html.twig', [
            'board' => $board,
        ]);
    }
    #[Route('/board/{board}/{post}', name: 'app_show')]
    public function show(Request $request, Post $post, Board $board, ReplyRepository $replyRepository, EntityManagerInterface $entityManager): Response {
        $replyForm = $this->createForm(ReplyType::class);
        $replyForm->handleRequest($request);
        if ($replyForm->isSubmitted() && $replyForm->isValid() && !$this->isGranted('ROLE_BANNED')) {
            $reply = $replyForm->getData();
            $user = $this->getUser();
            $reply->setAuthor($user)
                ->setPost($post);
            $entityManager->persist($reply);
            $entityManager->flush();
            $this->addFlash('success', 'Successfully replied.');
        }
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $replyRepository->getReplyPaginator($post, $offset);
        return $this->render('forum/show.html.twig', [
            'post' => $post,
            'replyForm' => $this->createForm(ReplyType::class),
            'replies' => $paginator,
            'previous' => $offset - ReplyRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + ReplyRepository::PAGINATOR_PER_PAGE)
        ]);
    }
    #[Route('/dupa')]
    public function search(PostRepository $postRepository): Response {
        dd($postRepository->findByTitle("test"));
    }


}
