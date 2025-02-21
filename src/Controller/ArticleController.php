<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

final class ArticleController extends AbstractController
{
    #[Route('/article/{id}', name: 'article_view', requirements: ['id' => '\d+'])]
    public function show(EntityManagerInterface $entityManager, int $id,Request $request): Response
    {
        $search = $request->query->get('search');
        if ($search){
            $qb = $entityManager->getRepository(Article::class)->createQueryBuilder('article');
            $qb->andWhere('article.name LIKE :query OR article.description LIKE :query')
            ->setParameter('query', '%'.$search.'%');
            $articles = $qb->getQuery()->getResult();
        }else{
            $articles = $entityManager->getRepository(Article::class)->findAll();
        }
        $nbArticlePerPage = 9;
        if(count($articles)>9){
            $articles = array_slice($articles,($id-1)*$nbArticlePerPage,($id-1)*$nbArticlePerPage+$nbArticlePerPage-1);
        }
        $totalarticle = count($entityManager->getRepository(Article::class)->findAll());
        $visible_first = true;
        if($id == 1){$visible_first = false;}
        $visible_endless = false;
        if($id < $totalarticle/9){$visible_endless = true;}
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'visible_first' => $visible_first,
            'visible_endless' => $visible_endless,
            'id' => $id
        ]);
    }
}
