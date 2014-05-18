<?php
// src/Van/WallBundle/Form/PostType.php

namespace Van\WallBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Van\UserBundle\Form\DataTransformer\UserToNumberTransformer;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $documentManager = $options['dm'];
        $transformer = new UserToNumberTransformer($documentManager);
        
        $builder
            ->add(
            $builder->create('to', 'hidden')
                ->addModelTransformer($transformer)
            )
            ->add('content', 'textarea', array(
                'label' => false
            ))
            ;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
        'data_class' => 'Van\WallBundle\Document\Post'
        ))
        ->setRequired(array(
            'dm',
        ))
        ->setAllowedTypes(array(
            'dm' => 'Doctrine\Common\Persistence\ObjectManager',
        ))
        ;
    }
    
    public function getName()
    {
        return 'post';
    }
}
