<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArticleController extends AbstractController
{
    #[Route('/article/{index}', name: 'article_view')]
    public function show(EntityManagerInterface $entityManager, int $index): Response
    {
        $article_page = array();
        for($ind = 0; $ind < 9; $ind++)
        {
            array_push($article_page, $ind + (9 * $index));
        }
        $articles = $entityManager->getRepository(Article::class)->findBy(array('id' => $article_page));
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }
}
