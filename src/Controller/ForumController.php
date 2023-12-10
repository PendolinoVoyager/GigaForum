<?php

namespace App\Controller;

use App\Entity\Board;
use App\Entity\Post;
use App\Repository\BoardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function board(Board $board): Response
    {
        return $this->render('forum/board.html.twig', [
            'board' => $board
        ]);
    }
    #[Route('/board/{board}/{post}', name: 'app_show')]
    public function show(Post $post): Response {
        return $this->render('forum/show.html.twig', [
            'post' => $post
        ]);
    }


}
