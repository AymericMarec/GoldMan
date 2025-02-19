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
        $articles = array();
        for($ind = 9*$id; $ind < 9*($id+1); $ind++)
        {
            array_push($articles, $ind);
        }
        $first_endless = $entityManager->getRepository(Article::class)->findby(array('id' => $articles));
        if(count($articles) > count($first_endless))
            {$visible_endless = false;}
        if($id <= 0){$visible_first = false;}
        else{$visible_first = true;}
        return $this->render('article/index.html.twig', [
            'articles' => $first_endless,
            'visible_first' => $visible_first,
            'visible_endless' => $visible_endless,
            'id' => $id
        ]);
    }
}
