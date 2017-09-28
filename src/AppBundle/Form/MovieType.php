<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class MovieType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imdbId', null,[
                "label"=>"Référence Internet Movie DataBase"
                ])
            ->add('title', null,[
                "label"=>"Nom du film",
                "attr"=>["placeolder"=> "Sharknado"]
                ])
            ->add('year')
            ->add('cast', null,[
                "label"=>"Acteurs"
            ])
            ->add('directors', null,[
                "label"=>"Réalisateurs"
            ])
            ->add('writers', null,[
                "label"=>"Scénaristes"
            ])
            ->add('plot', null,[
                "label"=>"Synopsis"
            ])
            ->add('rating' , null,[
                "label"=>"Note"
            ])
            ->add('votes')
            ->add('runtime', null,[
                "label"=>"Durée"
            ])
            ->add('trailerId', null,[
        "label"=>"identifiant Youtube de la bande annonce"
            ])
           // ->add('dateCreated')
            ->add('genres',EntityType::class,["multiple"=>true,
                   "expanded"=>true,
                   "class"=>"AppBundle:Genre",
                   "choice_label"=>"name"])

            ->add('submit',SubmitType::class, ["label" => "Envoyer !"]);

    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Movie'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_movie';
    }


}
