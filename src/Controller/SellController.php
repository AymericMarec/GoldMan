<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Article;
use \Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;
use App\Form\ArticleType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class SellController extends AbstractController{
    #[Route('/sell', name: 'SellPage',methods: 'GET')]
    public function index(): Response
    {
        $user = $this->getUser();
        if(!$user){
            return $this->redirectToRoute('login');
        }
        return $this->render('sell/index.html.twig', [
            'controller_name' => 'SellController',
        ]);
    }
    #[Route('/sell', name: 'CreateArticle',methods: 'POST')]
    public function CreateArticle(Request $request,EntityManagerInterface $entityManager, string $imageDirectory): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        $user = $this->getUser();
        $article->setCreatorID($user);
        $article->setName($request->request->get('name'));
        $article->setDescription($request->request->get('description'));
        $article->setPrice($request->request->get('price'));
        $article->setPublicationDate(new \DateTime());
        $article->setUid(Uuid::v4()->toRfc4122());
        
        // if ($form->isSubmitted() && $form->isValid()) {
        //     /** @var UploadedFile $imageFile */
        //     $imageFile = $form->get('upload_image')->getData();

        //     if ($imageFile) {
        //         $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

        //         $safeFilename = $slugger->slug($originalFilename);
        //         $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

        //         try {
        //             $imageFile->move($imageDirectory, $newFilename);
        //         } catch (FileException $e) {
        //             // ... handle exception if something happens during file upload
        //         }
        //         $article->setImage($newFilename);
        //     }
        //     return $this->redirectToRoute('app_image_list');
        // }
        
        // $entityManager->persist($article);
        $article->setImage("");
        $entityManager->flush();
        return $this->render('sell/index.html.twig', [
            'controller_name' => 'SellController',
            'form' => $form,
        ]);
    }
}