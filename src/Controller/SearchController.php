<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;

final class SearchController extends AbstractController{
    #[Route('/search', name: 'app_search')]
    public function index(Request $request,EntityManagerInterface $entityManager): Response
    {
        $search = $request->request->get('search');
        $qb = $entityManager->getRepository(Article::class)->createQueryBuilder('article');
        $qb->andWhere('article.name LIKE :query OR article.description LIKE :query')
        ->setParameter('query', '%'.$search.'%');
        $articles = $qb->getQuery()->getResult();

        return $this->render('search/index.html.twig', [
            'articles' => $articles,
            'search'=> $search
        ]);
    }
}
