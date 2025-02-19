<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Controller\BlogController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

final class ArticleController extends AbstractController
{
    #[Route('/article/{id}', name: 'article_view')]
    public function show(EntityManagerInterface $entityManager, int $id): Response
    {
        $visible_endless = true;
        $first_endless = $entityManager->getRepository(Article::class)->findAll();
        $articles = array();
        for($ind = 9*$id; $ind < 9*($id+1); $ind++)
        {
        if($ind != count($first_endless)-1)
        {array_push($articles, $first_endless[$ind]);}
        else{
            $visible_endless = false;
        break;
        }
        }
        if($id <= 0){$visible_first = false;}
        else{$visible_first = true;}
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'visible_first' => $visible_first,
            'visible_endless' => $visible_endless,
            'id' => $id
        ]);
    }
}
