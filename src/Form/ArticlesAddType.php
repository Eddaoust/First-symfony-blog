<?php

namespace App\Form;

use App\Entity\Articles;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticlesAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user_id'];

        $builder
            ->add('title')
            ->add('content')
            ->add('img')
            ->add('category', EntityType::class, [ // Champ du formulaire issu d'une entité
                'class' => Category::class, // Sélection de la classe
                'choice_label' => 'title' // Sélection du champ
            ])
            ->add('user', HiddenType::class, [ // Recherche d'une solution afin de passer l'id utilisateur en hidden
                'data' => $user,
                'data_class' => null
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
