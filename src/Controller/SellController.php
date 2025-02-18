<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Article;
use \Doctrine\ORM\EntityManagerInterface;
final class SellController extends AbstractController{
    #[Route('/sell', name: 'SellPage',methods: 'GET')]
    public function index(): Response
    {
        return $this->render('sell/index.html.twig', [
            'controller_name' => 'SellController',
        ]);
    }
    #[Route('/sell', name: 'CreateArticle',methods: 'POST')]
    public function CreateArticle(Request $request,EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $user = $this->getUser();
        $article->setCreatorID($user);
        $article->setName($request->request->get('name'));
        $article->setDescription($request->request->get('description'));
        $article->setPrice($request->request->get('price'));
        $article->setPublicationDate(new \DateTime());
        $article->setImage("");
        $entityManager->persist($article);
        $entityManager->flush();
        return $this->render('sell/index.html.twig', [
            'controller_name' => 'SellController',
        ]);
    }
}
