<?php

namespace IR\MediaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IR\MediaBundle\Form\DataTransformer\ProviderDataTransformer;
use IR\MediaBundle\Provider\Pool;

/**
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
abstract class MediaFormType extends AbstractType
{
    
    /**
     * @var \IR\MediaBundle\Provider\Pool $pool
     */     
    protected $pool;    
    
    /**
     * @var string $class
     */    
    protected $class;
    
    /**
     * Constructor.
     * 
     * @param \IR\MediaBundle\Provider\Pool $pool
     * @param string                        $class
     */
    public function __construct(Pool $pool, $class)
    {   
        $this->pool  = $pool;
        $this->class = $class;
    }  
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        $context = $this->pool->getContext($options['context']);
        
        $builder
            ->add('binaryContent', $options['widget'], array(
                'attr' => array('multiple' => 'multiple'),
                'label'              => 'form.media.binary_content', 
                'translation_domain' => 'IRMediaBundle',                 
            ))
            ->addModelTransformer(new ProviderDataTransformer($this->pool, array(
                'provider' => $options['provider'],
                'context'  => $options['context'],
            )))
        ;
    }    
    
    /**
     * {@inheritdoc}
     */       
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'widget'     => 'file',
            'intention'  => 'basic_information',
            'provider'   => '',
            'context'    => $this->pool->getDefaultContext(),            
        ));  
        
        $resolver->setAllowedValues(array(
            'context'  => array_keys($this->pool->getContexts()),
            'widget'   => array(
                'file',
                'text',
            ), 
        ));        
    }    
      
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'form';
    }   
}
