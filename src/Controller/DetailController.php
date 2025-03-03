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

#[Route('{_locale<%app.supported_locales%>}')]
final class DetailController extends AbstractController
{
    #[Route('/detail/{uid}', name: 'app_detail')]
    public function index(string $uid, EntityManagerInterface $entityManager): Response
    {
        dump("coucou");
        $article = $entityManager->getRepository(Article::class)->findOneBy(['uid' => $uid]);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }
        /** @var User $user */
        $user = $this->getUser();
        if(!$user){
            $editable = false;
        }else if($article->getCreatorID()->getId() == $user->getId() || in_array("ROLE_ADMIN",$user->getRoles())){
            $editable = true;
        }else {
            $editable = false;
        }

        $item = $entityManager->getRepository(Cart::class)->createQueryBuilder('c')
        ->where('c.articleID = :article')
        ->andWhere('c.UserID = :user')
        ->setParameter('article', $article)
        ->setParameter('user', $user)
        ->getQuery()
        ->getOneOrNullResult();

        if (!$item) {
            $incart = false;
        } else {
            $incart = true;
        }

        return $this->render('detail/index.html.twig', [
            'article' => $article,
            'user' => $user,
            "editable"=> $editable,
            "isincart"=> $incart,
        ]);
    }

    #[Route('/detail/{uid}/add-to-cart', name: 'app_add_to_cart',methods:'POST')]
    public function addToCart(string $uid, EntityManagerInterface $entityManager): Response
    {
        dump("coucou");
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
            dump("coucou");
            return $this->redirectToRoute('app_detail', ['uid' => $uid]);
        }
        $stock = $article->getStock();
        if($stock->getNbStock() <= 0){
            $this->addFlash('error', 'L\'article n\'est plus en stock');
            dump("coucou");
            return $this->redirectToRoute('app_detail', ['uid' => $uid]);
        }


        $cart = new Cart();
        $cart->setArticleID($article);
        $cart->setUserID($user);
        $cart->setQuantity(1);

        $stock->setNbStock($stock->getNbStock()-1);
        $entityManager->persist($stock);

        $entityManager->persist($cart);
        $entityManager->flush();
        dump("coucou");
        $this->addFlash('success', 'L\'article a été ajouté à votre panier.');
        dump("coucou2");
        return $this->redirectToRoute('cart');
        
    }
}
