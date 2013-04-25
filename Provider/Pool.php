<?php

namespace IR\MediaBundle\Provider;

use IR\MediaBundle\Model\MediaInterface;

/**
 * Pool.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class Pool
{
    /**
     * @var array $providers
     */
    protected $providers;

    /**
     * @var array $contexts
     */    
    protected $contexts;    

    /**
     * @var string $defaultContext
     */        
    protected $defaultContext;
    
    /**
     * Constructor.
     * 
     * @param string $context
     */    
    public function __construct($context)
    {
        $this->providers = array();
        $this->contexts = array();
        $this->defaultContext = $context;
    }
    
    /**
     * Checks if a named provider exists
     * 
     * @param string $name
     *
     * @return bool
     */
    public function hasProvider($name)
    {
        return isset($this->providers[$name]);
    }    
    
    /**
     * Gets a provider by its name
     * 
     * @throws \RuntimeException
     *
     * @param  string $name
     * 
     * @return \IR\MediaBundle\Provider\MediaProviderInterface
     */
    public function getProvider($name)
    {
        if (!isset($this->providers[$name])) {
            throw new \RuntimeException(sprintf('unable to retrieve the provider named : `%s`', $name));
        }

        return $this->providers[$name];
    }
    
    /**
     * Gets providers
     * 
     * @return \IR\MediaBundle\Provider\MediaProviderInterface[]
     */
    public function getProviders()
    {
        return $this->providers;
    }    
    
    /**
     * Checks if a named context exists
     * 
     * @param string $name
     *
     * @return bool
     */
    public function hasContext($name)
    {
        return isset($this->contexts[$name]);
    }

    /**
     * Gets a context by its name
     * 
     * @param string $name
     *
     * @return array|null
     */
    public function getContext($name)
    {
        if (!$this->hasContext($name)) {
            return null;
        }

        return $this->contexts[$name];
    }

    /**
     * Returns the context list
     *
     * @return array
     */
    public function getContexts()
    {
        return $this->contexts;
    }
    
    /**
     * Gets the default context
     * 
     * @return string
     */
    public function getDefaultContext()
    {
        return $this->defaultContext;
    }    
    
    /**
     * Getsthe correct format name : context_format
     * 
     * @param IR\MediaBundle\Model\MediaInterface $media
     * @param string                              $format
     * 
     * @return string
     */    
    public function getFormatName(MediaInterface $media, $format)
    {
        $baseName = $media->getContext().'_';
        if (substr($format, 0, strlen($baseName)) == $baseName) {
            return $format;
        }

        return $baseName.$format;
    }    
    
    /**
     * Gets the format settings of a named context
     * 
     * @param string $contextName
     * @param string $formatName
     * 
     * @return string
     */      
    public function getContextFormat($contextName, $formatName)
    {
        $context = $this->getContext($contextName);

        if (!$context) {
            return null;
        }
        
        return isset($context['formats'][$formatName]) ? $context['formats'][$formatName] : false;
    }     
    
    /**
     * Add a provider
     * 
     * @param string                                          $name
     * @param \IR\MediaBundle\Provider\MediaProviderInterface $provider
     *
     * @return void
     */
    public function addProvider($name, MediaProviderInterface $provider)
    {
        $this->providers[$name] = $provider;
    }    
    
    /**
     * Add a context
     * 
     * @param string $name
     * @param array  $providers
     * @param array  $formats
     *
     * @return void
     */
    public function addContext($name, array $formats = array())
    {
        if (!$this->hasContext($name)) {
            $this->contexts[$name] = array(
                'formats'   => array(),
            );
        }

        $this->contexts[$name]['formats'] = $formats;
    }   
}