<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;

#[Route('{_locale<%app.supported_locales%>}')]
final class EditController extends AbstractController{
    #[Route('/edit/{uid}', name: 'editArticle',methods:'PUT')]
    public function SaveChanges(string $uid,ArticleRepository $articleRepository,Request $request,EntityManagerInterface $em): Response
    {
        $article = $articleRepository->findOneBy(['uid' => $uid]);
        $data = json_decode($request->getContent(), false);
        $article->setName($data->name);
        $article->setDescription($data->description);
        $article->setPrice($data->price);

        $em->persist($article);
        $em->flush();
        return new Response("OK");
    }
    #[Route('/edit/{uid}', name: 'deleteArticle',methods:'DELETE')]
    public function DeleteUser(EntityManagerInterface $em,Request $request,string $uid)
    {
        $article = $em->getRepository(Article::class)->findOneBy(['uid' => $uid]);
        $em->remove($article);
        $em->flush();
        return new Response("OK");
    }
}
