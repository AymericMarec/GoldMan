<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use \Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Exception;
use App\Repository\UserRepository;

#[Route('{_locale<%app.supported_locales%>}')]
final class AccountController extends AbstractController{
    #[Route('/account', name: 'AccountPage',methods:'GET')]
    public function ShowUser(Request $request,UserRepository $userRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        //if user is not logged in
        if (!$user) {
            return $this->redirectToRoute('login');
        }
        $articles = $user->getArticles();
        $invoices = $user->getInvoices();
        //Load Page with UserInfo
        return $this->render('account/index.html.twig', [
            'user' =>$user,
            'articles' =>$articles,
            'invoices' =>$invoices,
        ]);
    }
    #[Route('/account/{uid}', name: 'OtherAcountPage',methods:'GET')]
    public function ShowOtherUser(Request $request,UserRepository $userRepository,string $uid): Response
    {
        $user = $userRepository->findOneBy(['uid' => $uid]);
        $articles = $user->getArticles();
        //Load Page with OtherUserInfo
        return $this->render('account/view.html.twig', [
            'user' => $user,
            'articles' => $articles
        ]);
    }




    #[Route('/account', name: 'ChangeInfo',methods:'POST')]
    public function ChangeInfo(Request $request,EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute("AccountPage");
        }
        $formType = $request->request->get('type');
        switch ($formType) {
            case 'email':
                $newEmail = $request->request->get('email');
                $user->setEmail($newEmail);
                break;
            case 'password':
                $newPassword = $request->request->get('password');
                $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
                $user->setPassword($hashedPassword);
                break;
            case 'username':
                $newUserName = $request->request->get('username');
                $user->setUsername($newUserName);
                break;
            case 'profilePicture':
                $newUserName = $request->request->get('profilePicture');
                $user->setUsername($newUserName);
                break;
            case 'balance':
                $addBalance = $request->request->get('balance');
                $user->setBalance($user->getBalance()+$addBalance);
                break;
            default:
                throw new Exception('bad Forme Type',$formType);
        }
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute("AccountPage");
    }
}
