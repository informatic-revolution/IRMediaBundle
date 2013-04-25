<?php

namespace IR\MediaBundle\Twig\Extension;

use IR\MediaBundle\Model\MediaInterface;
use IR\MediaBundle\Provider\Pool;
use IR\MediaBundle\Twig\TokenParser\MediaTokenParser;
use IR\MediaBundle\Twig\TokenParser\ThumbnailTokenParser;
use IR\MediaBundle\Twig\TokenParser\PathTokenParser;

class MediaExtension extends \Twig_Extension
{

    /**
     * @var \IR\MediaBundle\Provider\Pool $pool
     */    
    protected $pool;
    
    /**
     * @var array $ressources
     */        
    protected $ressources;
    
    /**
     * @var \Twig_Environment $environment
     */      
    protected $environment;
    
    /**
     * @param \IR\MediaBundle\Provider\Pool $pool
     */
    public function __construct(Pool $pool)
    {
        $this->ressources = array();
        $this->pool = $pool;
    }    
    
    /**
     * {@inheritdoc}
     */
    public function getTokenParsers()
    {
        return array(
            new MediaTokenParser($this->getName()),
            new ThumbnailTokenParser($this->getName()),
            new PathTokenParser($this->getName()),
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }    
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ir_media';
    }
    
    /**
     * @param mixed $media
     *
     * @return null|\IR\MediaBundle\Model\MediaInterface
     */
    private function getMedia($media)
    {
        if (!$media instanceof MediaInterface) {
            return false;
        }

        return $media;
    }   
    
    /**
     * @param string $template
     * @param array $parameters
     *
     * @return mixed
     */
    public function render($template, array $parameters = array())
    {   
        if (!isset($this->ressources[$template])) {
            $this->ressources[$template] = $this->environment->loadTemplate($template);
        }
        
        return $this->ressources[$template]->render($parameters);
    }     
    
    /**
     * @param \IR\MediaBundle\Model\MediaInterface $media
     * @param string                               $format
     * @param array                                $options
     *
     * @return string
     */
    public function media($media = null, $format, $options = array())
    {
        $media = $this->getMedia($media);

        if (!$media) {
            return '';
        }

        $provider = $this->pool
            ->getProvider($media->getProviderName());
        
        $format = $this->pool
            ->getFormatName($media, $format);
        
        $format_definition = $this->pool
            ->getContextFormat($media->getContext(), $format);
        
        // build option
        $defaultOptions = array(
        //    'title' => $media->getName(),
        );        
        
        if ($format_definition['width']) {
            $defaultOptions['width'] = $format_definition['width'];
        }
        if ($format_definition['height']) {
            $defaultOptions['height'] = $format_definition['height'];
        }        
                
        $options = array_merge($defaultOptions, $options);
        
        $options['src'] = $provider->generatePublicUrl($media, $format);
        
        return $this->render($provider->getTemplate('helper_view'), array(
            'media'   => $media,
            'format'  => $format,
            'options' => $options,
        ));
    }    
        
    /**
     * Returns the thumbnail for the provided media
     *
     * @param \IR\MediaBundle\Model\MediaInterface $media
     * @param string $format
     * @param array $options
     *
     * @return string
     */
    public function thumbnail($media = null, $format, $options = array())
    {
        $media = $this->getMedia($media);

        if (!$media) {
            return '';
        }

        $provider = $this->pool
            ->getProvider($media->getProviderName());
        
        $format = $this->pool
            ->getFormatName($media, $format);
        
        $format_definition = $this->pool
            ->getContextFormat($media->getContext(), $format);
        
        // build option
        $defaultOptions = array(
        //    'title' => $media->getName(),
        );

        if ($format_definition['width']) {
            $defaultOptions['width'] = $format_definition['width'];
        }
        if ($format_definition['height']) {
            $defaultOptions['height'] = $format_definition['height'];
        }

        $options = array_merge($defaultOptions, $options);

        $options['src'] = $provider->generatePublicUrl($media, $format);
        
        return $this->render($provider->getTemplate('helper_thumbnail'), array(
            'media'   => $media,
            'options' => $options,
        ));
    }
    
    /**
    * @param \Sonata\MediaBundle\Model\MediaInterface $media
    * @param string $format
    *
    * @return string
    */
    public function path($media = null, $format)
    {
        $media = $this->getMedia($media);
        
        if (!$media) {
             return '';
        }

        $provider = $this->pool
            ->getProvider($media->getProviderName());

        $format = $this->pool
            ->getFormatName($media, $format);
        
        return $provider->generatePublicUrl($media, $format);
    }
}
