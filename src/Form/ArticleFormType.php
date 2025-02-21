<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Stock;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('price')
            ->add('image', FileType::class, [
                'label' => 'Image (jpg, png, jpeg, webp, gif)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File(
                        maxSize: '4096k',
                        mimeTypes: [
                            'image/jpg',
                            'image/png',
                            'image/jpeg',
                            'image/webp',
                            'image/gif',
                        ],
                        mimeTypesMessage: 'Please upload a valid Image.',
                    )],])
                    ->add('stock', EntityType::class, [
                        'class' => Stock::class,
                        'choice_label' => 'id',
                    ])
            ->add('save' , SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
