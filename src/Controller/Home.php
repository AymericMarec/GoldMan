<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Home extends AbstractController
{
    #[Route('/')]
    public function homepage(): Response
    {

        $Article = [
            'name' => 'Projet Lapine',
            'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSHN_UOzvswcq9OhJUivaRDddRBqeXu5IzhSttBGW7nwNxAmPD6kr6S4McG43T3vJTpH10&usqp=CAU',
            'price' => 0,
            'vendor' => 'Bronya'
        ];
        return $this->render('main/homepage.html.twig', [
            'Article' => $Article,
        ]);
    }
}