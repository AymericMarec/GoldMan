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

final class AccountController extends AbstractController{
    #[Route('/account', name: 'AccountPage',methods:'GET')]
    public function index(Request $request,UserRepository $userRepository): Response
    {
        $userToShow = $request->query->get('uid');
        if($userToShow == null){
            return $this->ShowUser();
        }else {
            return $this->ShowOtherUser($userToShow, $userRepository);
        }
    }


    public function ShowUser():Response{
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('login');
        }
        $email = $user->getEmail();
        $articles = $user->getArticles();
        $bills = $user->getInvoices();
        //Load Page with UserInfo
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }
    public function ShowOtherUser($uid,UserRepository $userRepository):Response{
        $user = $userRepository->findOneBy(['uid' => $uid]);
        if(!$user){
            //Load Error page , user doesnt exist
        }
        //Load Page with OtherUserInfo
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }

    #[Route('/account', name: 'ChangeInfo',methods:'POST')]
    public function ChangeInfo(Request $request,EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->ShowUser();
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
        return $this->ShowUser();
    }
}
