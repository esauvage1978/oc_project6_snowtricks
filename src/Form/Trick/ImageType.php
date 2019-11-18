<?php

namespace App\Form\Trick;

use App\Entity\Image;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class,
                [
                    'label'			=> 'Choisir le fichier'
                ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
                $image = $event->getData();
                $form = $event->getForm();

                if($image && $image->getId() !== null) {
                    $form->add('name', HiddenType::class);
                    $form->add('extension', HiddenType::class);
                    $form->remove('file');
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
