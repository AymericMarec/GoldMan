<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

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
            ->add('stock', IntegerType::class, [
                'label' => 'Quantité en stock',
                'mapped' => false,
                'attr' => [
                    'min' => 0,
                    'placeholder' => 'Ex: 1'
                ]
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
