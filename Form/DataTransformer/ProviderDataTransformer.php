<?php

namespace IR\MediaBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use IR\MediaBundle\Model\MediaInterface;
use IR\MediaBundle\Provider\Pool;

/**
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class ProviderDataTransformer implements DataTransformerInterface
{
    
    /**
     * @var \IR\MediaBundle\Provider\Pool $pool
     */     
    protected $pool;
    
    /**
     * Constructor.
     * 
     * @param \IR\MediaBundle\Provider\Pool $pool
     * @param array $options
     */
    public function __construct(Pool $pool, array $options = array())
    {
        $this->pool    = $pool;
        $this->options = $options;
    }
    
    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        return $value;
    }
    
    /**
     * {@inheritdoc}
     */
    public function reverseTransform($media)
    {
        if (!$media instanceof MediaInterface) {
            return $media;
        }
        
        if (/*!$media->getProviderName() &&*/ isset($this->options['provider'])) {
            $media->setProviderName($this->options['provider']);
        }
        
        if (/*!$media->getContext() &&*/ isset($this->options['context'])) {
            $media->setContext($this->options['context']);
        }
        
        $provider = $this->pool->getProvider($media->getProviderName());
        
        $provider->transform($media);
        
        return $media;
    }    
}