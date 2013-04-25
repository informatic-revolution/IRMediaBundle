<?php

namespace IR\MediaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use IR\MediaBundle\Provider\Pool;

/**
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class ImageFormType extends MediaFormType
{    
    
    /**
     * {@inheritdoc}
     *//*
    public function getDefaultOptions(array $options)
    {   
        return array_merge(parent::getDefaultOptions($options), array(
            'provider' => 'ir_media.provider.image',     
        ));
    }*/
    
    /**
     * {@inheritdoc}
     *//*
    public function getAllowedOptionValues(array $options)
    {        
        return array_merge(parent::getAllowedOptionValues($options), array(
            'provider' => array(
                'ir_media.provider.image',
            )
        ));
    }*/     
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ir_media_image_type';
    }    
}
