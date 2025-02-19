<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArticleController extends AbstractController
{
    #[Route('/article', name: 'article_view')]
    public function show(EntityManagerInterface $entityManager): Response
    {
        $articles = $entityManager->getRepository(Article::class)->findAll();
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
 * @Route("/article", name: "article_index")
 * @param Index $index
 * @param Articles $articles
 * @param Max $max
 */
    function Index_Page(int $index,array $articles, int $max)
    {
        $article_page = array();
        for($page = 0; $page < $max; $page++)
        {
            try{
                array_push($article_page, $articles[$page * $index]);
            }
            catch (Exception){
                return $article_page;
            }
        }
        return $article_page;
    }
}
