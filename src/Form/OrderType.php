<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Transporteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user=$options['user'];
        $builder
            ->add('address',EntityType::class,[
                'label'=>'votre adresse',
                'class'=>Adresse::class,
                'choices'=>$user->getAdresses(),
                'required'=>true,
                'multiple'=>false,
                'expanded'=>true,

            ])
            ->add('transporteur',EntityType::class,[
                'label'=>'votre transporteur',
                'class'=>Transporteur::class,
                // 'choices'=>$user->getAdresses(),
                'required'=>true,
                'multiple'=>false,
                'expanded'=>true,

            ])
            ->add('submit',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'user'=>[],
        ]);
    }
}
