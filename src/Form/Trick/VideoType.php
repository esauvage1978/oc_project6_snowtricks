<?php

namespace App\Form\Trick;

use App\Entity\Video;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class VideoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code',		TextType::class, array(
                'label'			=> 'Id de la vidéo'
            ))
            ->add('plateform', 	ChoiceType::class, array(
                'label'			=> 'Plateforme',
                'choices' 		=> [
                    ucwords( Video::YOUTUBE) => Video::YOUTUBE,
                    ucwords(Video::DAILYMOTION) => Video::DAILYMOTION
                ]
            ))
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
                $video = $event->getData();
                $form = $event->getForm();

                if($video && $video->getId() !== null) {
                    $form->add('code', HiddenType::class);
                    $form->add('plateform', HiddenType::class);
                }
            })
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}
