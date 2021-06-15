<?php

namespace App\Form;

use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class AdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'label'=>'nom de l\'adresse',
                'attr'=>[
                    'placeholder'=>'Maison',]
            ])
            ->add('firstName',TextType::class,[
                'label'=>'prénom',
                'attr'=>[
                    'placeholder'=>'Julie',]
            ])
            ->add('lastName',TextType::class,[
                'label'=>'nom ',
                'attr'=>[
                    'placeholder'=>'Lombard',]
            ])
            ->add('societe',TextType::class,[
                'label'=>'societe',
                'attr'=>[
                    'placeholder'=>'ncdsm',]
            ])
            ->add('address',TextType::class,[
                'label'=>'adresse',
                'attr'=>[
                    'placeholder'=>'6 rue paradis',]
            ])
            ->add('postalCode',TextType::class,[
                'label'=>'code postal',
                'attr'=>[
                    'placeholder'=>'13500',]
            ])
            ->add('city',TextType::class,[
                'label'=>'ville',
                'attr'=>[
                    'placeholder'=>'martigues',]
            ])
            ->add('country',CountryType::class,[
                'label'=>'pays',
                'attr'=>[
                    'placeholder'=>'Maison',]
            ])
            ->add('phone',TelType::class,[
                'label'=>'tel',
                'attr'=>[
                    'placeholder'=>'tel',]
            ])
           
            ->add('envoyer',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
