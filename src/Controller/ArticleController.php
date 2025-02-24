<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('{_locale<%app.supported_locales%>}')]
final class ArticleController extends AbstractController
{
    #[Route('/article/{id}', name: 'article_view', requirements: ['id' => '\d+'])]
    public function show(EntityManagerInterface $entityManager, int $id,Request $request): Response
    {
        $search = $request->query->get('search');
        $minPrice = $request->request->get('minPrice');
        $sortOrder = $request->request->get('priceSort');

        $qb = $entityManager->getRepository(Article::class)->createQueryBuilder('article');
        if ($search){
            $qb->andWhere('article.name LIKE :query OR article.description LIKE :query')
            ->setParameter('query', '%'.$search.'%');
            $articles = $qb->getQuery()->getResult();
        }
        if ($minPrice) {
            $qb->andWhere('article.price >= :minPrice')
               ->setParameter('minPrice', $minPrice);
        }
        if ($sortOrder) {
            $qb->orderBy('article.price', $sortOrder);
        }
        if(!$search && !$minPrice && !$sortOrder){
            $articles = $entityManager->getRepository(Article::class)->findAll();
        }
        $articles = $qb->getQuery()->getResult();
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

    #[Route('/article/sort', name: 'article_sort', methods: ['POST'])]
    public function sortArticles(Request $request, EntityManagerInterface $entityManager): Response
    {
        $minPrice = $request->request->get('minPrice');
        $sortOrder = $request->request->get('priceSort');
        return $this->redirect('http://stackoverflow.com');
        $qb = $entityManager->getRepository(Article::class)->createQueryBuilder('a');
        
        if ($minPrice) {
            $qb->andWhere('a.price >= :minPrice')
               ->setParameter('minPrice', $minPrice);
        }
        if ($sortOrder) {
            $qb->orderBy('a.price', $sortOrder);
        }
        $articles = $qb->getQuery()->getResult();

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'id' => 1, // Ajustez selon votre logique de pagination
            'visible_first' => false,
            'visible_endless' => false
        ]);
    }
}
