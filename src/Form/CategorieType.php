<?php

namespace App\Form;

use App\Entity\Categories;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'attr' =>[
                    'class' => 'form-control ',
                   
                ],
                'label'=>'Intitulé',
                'label_attr'=>[
                    'class' => 'form-label'],
              
            ])
            ->add('description', TextareaType::class,[
                'attr' =>[
                    'class' => 'form-control',
                    
                ],
                'label'=>'Description',
                'label_attr'=>[
                    'class' => 'form-label mt-4']
            ])
            ->add('submit', SubmitType::class, [
                'attr' =>[
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => 'Créer catégorie',
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categories::class,
        ]);
    }
}
