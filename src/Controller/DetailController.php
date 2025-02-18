<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;


final class DetailController extends AbstractController
{
    #[Route('/detail/{id}', name: 'app_detail')]
    public function index(int $id, EntityManagerInterface $entityManager, Security $security): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        $user = $security->getUser();

        return $this->render('detail/index.html.twig', [
            'article' => $article,
            'user' => $user,
        ]);
    }

    #[Route('/detail/{id}/add-to-cart', name: 'app_add_to_cart')]
    public function addToCart(int $id, EntityManagerInterface $entityManager, Security $security): RedirectResponse
    {
        $user = $security->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        if ($user === $article->getCreatorID()) {
            $this->addFlash('error', 'Vous ne pouvez pas ajouter votre propre article au panier.');
            return $this->redirectToRoute('app_detail', ['id' => $id]);
        }

        $cart = new Cart();
        $cart->setArticleID($article);
        $cart->setUserID($user);

        $entityManager->persist($cart);
        $entityManager->flush();

        $this->addFlash('success', 'L\'article a été ajouté à votre panier.');

        return $this->redirectToRoute('app_detail', ['id' => $id]);
    }
}
