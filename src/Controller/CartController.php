<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Article;
use App\Entity\User;
use App\Entity\Invoice;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

#[Route('{_locale<%app.supported_locales%>}')]
final class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart')]
    public function ShowCart(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('login');
        }
        $carts = $user->getCarts();
        $totalSold = 0;
        foreach($carts as $id => $cart) {
            $article = $cart->getArticleID();
            $totalSold += $article->getPrice() * $cart->getQuantity();
        }
        return $this->render('cart/cart.html.twig', [
            'carts' => $carts,
            'total' => $totalSold
        ]);
    }
    #[Route('/cart/validate', name: 'validate',methods:'GET')]
    public function ShowValidate(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('login');
        }
        $carts = $user->getCarts();
        $totalSold = 0;
        foreach($carts as $id => $cart) {
            $article = $cart->getArticleID();
            $totalSold += $article->getPrice() * $cart->getQuantity();
        }
        $CanBuy = true;
        if ($totalSold > $user->getBalance()){
            $CanBuy = false;
        }
        return $this->render('cart/validate.html.twig', [
            'carts' => $carts,
            'canBuy'=> $CanBuy,
            'total' => $totalSold
        ]);
    }
    #[Route('/cart/validate', name: 'CreateInvoice',methods:'POST')]
    public function CreateInvoice(EntityManagerInterface $em,Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $carts = $user->getCarts();
        $totalSold = 0;
        foreach($carts as $id => $cart) {
            $article = $cart->getArticleID();
            $totalSold += $article->getPrice();
            $em->remove($cart);
        }
        
        $user->setBalance($user->getBalance()-$totalSold);
        $invoice = new Invoice();
        $invoice->setUserId($user);
        $invoice->setTransactionDate(new \DateTime());
        $invoice->setAmount($totalSold);
        $invoice->setBillingAdress($request->request->get('adress'));
        $invoice->setBillingCity($request->request->get('city'));
        $invoice->setPostCode($request->request->get('postalCode'));
        $em->persist($invoice);

        $em->flush();
        return $this->redirectToRoute('home');
    }

    #[Route('/cart/remove/{uid}', name: 'RemoveItem',methods:'GET')]
    public function RemoveInvoice(EntityManagerInterface $em, string $uid): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $article = $em->getRepository(Article::class)->findOneBy(['uid' => $uid]);

        
        $item = $em->getRepository(Cart::class)->createQueryBuilder('c')
            ->where('c.articleID = :article')
            ->andWhere('c.UserID = :user')
            ->setParameter('article', $article)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();


        $em->remove($item);
        $em->flush(); 

        $stock = $article->getStock();
        $stock->setNbStock($stock->getNbStock()+$item->getQuantity());
        $em->persist($stock);
        $em->flush();

        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/addQt/{uid}', name: 'addQt', methods: 'GET')]
    public function addQt(EntityManagerInterface $em, string $uid): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $article = $em->getRepository(Article::class)->findOneBy(['uid' => $uid]);

        $item = $em->getRepository(Cart::class)->createQueryBuilder('c')
            ->where('c.articleID = :article')
            ->andWhere('c.UserID = :user')
            ->setParameter('article', $article)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();


        $stock = $article->getStock();
        
        if ($stock->getNbStock() > 0) {
            $item->setQuantity($item->getQuantity() + 1);
            $stock->setNbStock($stock->getNbStock()-1);
            $em->persist($item);
            $em->persist($stock);
            $em->flush();
        } else {
            $this->addFlash('error', 'Maximum de stock atteint');
        }
        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/removeQt/{uid}', name: 'removeQt', methods: 'GET')]
    public function removeQt(EntityManagerInterface $em, string $uid): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        
        $article = $em->getRepository(Article::class)->findOneBy(['uid' => $uid]);
        $item = $em->getRepository(Cart::class)->createQueryBuilder('c')
            ->where('c.articleID = :article')
            ->andWhere('c.UserID = :user')
            ->setParameter('article', $article)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();


        $stock = $article->getStock();
        
        if ($item->getQuantity() > 1) {
            $item->setQuantity($item->getQuantity() - 1);
            $stock->setNbStock($stock->getNbStock()+1);
            $em->persist( $stock);
            $em->persist($item);
        } else {
            return $this->redirectToRoute('RemoveItem', ['uid'=> $article->getUid()]);
        }

        $em->flush();
        
        
        return $this->redirectToRoute('cart');
    }

}
