<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;

final class AdminController extends AbstractController{
    #[Route('/admin/user', name: 'adminUserPage',methods:'GET')]
    public function ShowUser(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('admin/user.html.twig', [
            'users' => $users,   
        ]);
    }
    #[Route('/admin/user', name: 'DeleteUser',methods:'DELETE')]
    public function DeleteUser(EntityManagerInterface $em,Request $request)
    {
        $uid = $request->query->get('uid');
        $user = $em->getRepository(User::class)->findOneBy(['uid' => $uid]);
        $em->remove($user);
        $em->flush();
        return new Response("OK");
    }
    #[Route('/admin/user', name: 'SwichAdmin',methods:'PUT')]
    public function SwichAdmin(EntityManagerInterface $em,Request $request)
    {
        $uid = $request->query->get('uid');
        $user = $em->getRepository(User::class)->findOneBy(['uid' => $uid]);
        $user->SwitchRole();
        $em->persist($user);
        $em->flush();
        return new Response("OK");
    }

    #[Route('/admin/article', name: 'adminArticlePage',methods:'GET')]
    public function ShowArticle(ArticleRepository $ArticleRepository): Response
    {
        $articles = $ArticleRepository->findAll();
        return $this->render('admin/article.html.twig', [
            'articles' => $articles
        ]);
    }
}
