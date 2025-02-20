<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Cart;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;


final class DetailController extends AbstractController
{
    #[Route('/detail/{uid}', name: 'app_detail')]
    public function index(string $uid, EntityManagerInterface $entityManager): Response
    {
        $article = $entityManager->getRepository(Article::class)->findOneBy(['uid' => $uid]);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }
        /** @var User $user */
        $user = $this->getUser();
        if($article->getCreatorID()->getId() == $user->getId() || in_array("ROLE_ADMIN",$user->getRoles())){
            $editable = true;
        }else {
            $editable = false;
        }
        return $this->render('detail/index.html.twig', [
            'article' => $article,
            'user' => $user,
            "editable"=> $editable
        ]);
    }

    #[Route('/detail/{uid}/add-to-cart', name: 'app_add_to_cart')]
    public function addToCart(string $uid, EntityManagerInterface $entityManager): RedirectResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $article = $entityManager->getRepository(Article::class)->findOneBy(['uid' => $uid]);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        if ($user === $article->getCreatorID()) {
            $this->addFlash('error', 'Vous ne pouvez pas ajouter votre propre article au panier.');
            return $this->redirectToRoute('app_detail', ['uid' => $uid]);
        }

        $cart = new Cart();
        $cart->setArticleID($article);
        $cart->setUserID($user);

        $entityManager->persist($cart);
        $entityManager->flush();

        $this->addFlash('success', 'L\'article a été ajouté à votre panier.');

        return $this->redirectToRoute('app_detail', ['uid' => $uid]);
    }
}
