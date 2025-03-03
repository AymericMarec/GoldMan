<?php

namespace App\Controller;

use App\Entity\Cart;
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
            $totalSold += $article->getPrice();
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
            $totalSold += $article->getPrice();
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
            $stock = $article->getStock();
            $stock->setNbStock($stock->getNbStock()-1);
            $em->persist($stock);
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

    #[Route('/cart/remove', name: 'RemoveItem',methods:'POST')]
    public function RemoveInvoice(EntityManagerInterface $em, string $uid, int $nb): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $article = $entityManager->getRepository(Article::class)->findOneBy(['uid' => $uid]);

        
        $item = $entityManager->getRepository(Cart::class)->findOneBy(
            ['articleID' => $article,
            'user' => $user]
        );

        $em->remove($item);
        $em->flush(); 

        $stock = $article->getStock();
        $stock->setNbStock($stock->getNbStock()-$nb);
        $em->persist($stock);
        $em->flush();

        return $this->redirectToRoute('cart');
    }

}
