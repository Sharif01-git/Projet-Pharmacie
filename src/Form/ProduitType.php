<?php

namespace App\Form;

use App\Entity\Produits;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\LessThan;
use Vich\UploaderBundle\Form\Type\VichImageType;
use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

class ProduitType extends AbstractType
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
            ->add('prix', MoneyType::class,[
                'attr' =>[
                    'class' => 'form-control',
                    
                ],
                'label'=>'Prix',
                'label_attr'=>[
                    'class' => 'form-label mt-4']
            ])
            ->add('stock', IntegerType::class, [
                
                'attr' =>[
                    'class' => 'form-control',
                    'min'=>1,
                    'max'=>200
                ],
                'label'=>'Nombre en stock',
                'label_attr'=>[
                    'class' => 'form-label mt-4'],
                'constraints' =>[
                    new Assert\Positive(),
                    new Assert\LessThan(201)
                ],
            ])

            ->add('categories', EntityType::class,[ 
                'attr' =>[
                    'class' => 'form-control'
                ],
                'class'=> Categories::class,
                'query_builder' => function (CategoriesRepository $r){
                    return $r->createQueryBuilder('i')
                                ->orderBy('i.nom', 'AsC');
                },
                'choice_label'=>'nom',
                'label'=>'Catégories',
               
              
                'label_attr'=>[
                    'class' => 'form-label mt-4 '],

            ])

            ->add('imageFile', VichImageType::class,[
                'label'=>'Image',
                'label_attr'=>[
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('submit',SubmitType::class, [
                'attr' =>[
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => 'Ajouter produit',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produits::class,
        ]);
    }
}
