<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

final class Home extends AbstractController
{
    #[Route('/', name: 'home')]
    public function homepage(EntityManagerInterface $entityManager): Response
    {  
        $first_endless = $entityManager->getRepository(Article::class)->findby(array(),array('id'=>'DESC'),4);
        return $this->render('main/homepage.html.twig', [
            'articles' => $first_endless,
        ]);
    }
}