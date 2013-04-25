<?php

namespace IR\MediaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class IRMediaExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        
        foreach (array('listener', 'form', 'validator', 'twig', 'cdn', 'gaufrette', 'orm', 'pool', 'provider', 'generator', 'thumbnail', 'resizer') as $basename) {
            $loader->load(sprintf('%s.yml', $basename));
        }        
        
        $bundles = $container->getParameter('kernel.bundles');
        
        if (!isset($bundles['LiipImagineBundle'])) {
            $container->removeDefinition('ir_media.thumbnail.liip_imagine');
        }        
        
        if (!array_key_exists($config['default_context'], $config['contexts'])) {
            throw new \InvalidArgumentException(sprintf('IRMediaBundle - Invalid default context : %s, available : %s', $config['default_context'], json_encode(array_keys($config['contexts']))));
        }        
        
        $pool = $container->getDefinition('ir_media.pool');
        $pool->replaceArgument(0, $config['default_context']);        
                
        $container->setParameter('ir_media.resizer.simple.adapter.mode', $config['resizer']['simple']['mode']);
        $container->setParameter('ir_media.resizer.square.adapter.mode', $config['resizer']['square']['mode']);
        
        $this->configureParameterClass($container, $config);
        $this->configureContexts($container, $config);
        $this->configureCdn($container, $config);
        $this->configureFilesystemAdapter($container, $config);
        $this->configureProviders($container, $config);
    }  
    
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param array                                                   $config
     */
    public function configureParameterClass(ContainerBuilder $container, array $config)
    {
        $container->setParameter('ir_media.model.media.class', $config['class']['media']);
    }
    
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param array                                                   $config
     */
    public function configureContexts(ContainerBuilder $container, array $config)
    {        
        $pool = $container->getDefinition('ir_media.pool');
        
        foreach ($config['contexts'] as $name => $settings) {
            $formats = array();

            foreach ($settings['formats'] as $format => $value) {
                $formats[$name.'_'.$format] = $value;
            }

            $pool->addMethodCall('addContext', array($name, $formats));
        }         
    }    
    
    /**
     * Inject CDN dependency to default provider
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param array $config
     *
     * @return void
     */
    public function configureCdn(ContainerBuilder $container, array $config)
    {
        // add the default configuration for the server cdn
        if ($container->hasDefinition('ir_media.cdn.server') && isset($config['cdn']['server'])) {
            $container->getDefinition('ir_media.cdn.server')
                ->replaceArgument(0, $config['cdn']['server']['path'])
            ;
        } else {
            $container->removeDefinition('ir_media.cdn_server');
        }
    }  
    
    /**
     * Inject filesystem dependency to default provider
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param array                                                   $config
     *
     * @return void
     */
    public function configureFilesystemAdapter(ContainerBuilder $container, array $config)
    {
        // add the default configuration for the local filesystem
        if ($container->hasDefinition('ir_media.adapter.filesystem.local') && isset($config['filesystem']['local'])) {
            $container->getDefinition('ir_media.adapter.filesystem.local')
                ->addArgument($config['filesystem']['local']['directory'])
                ->addArgument($config['filesystem']['local']['create'])
            ;
        } else {
            $container->removeDefinition('ir_media.adapter.filesystem.local');
        }
    } 
    
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param array $config
     */
    public function configureProviders(ContainerBuilder $container, $config)
    {   
        // Attach General Arguments
        foreach ($container->findTaggedServiceIds('ir_media.provider') as $id => $attributes) {
            foreach($config['providers'] as $name => $conf) {
                if ($conf['service'] == $id) {
                    $definition = $container->getDefinition($id);
                    
                    $definition
                        ->replaceArgument(1, new Reference($conf['filesystem']))
                        ->replaceArgument(2, new Reference($conf['cdn']))
                        ->replaceArgument(3, new Reference($conf['generator']))
                        ->replaceArgument(4, new Reference($conf['thumbnail']))
                    ;
                   
                    if ($conf['resizer']) {
                        $definition->addMethodCall('setResizer', array(new Reference($conf['resizer'])));
                    }
                }
            }
        }    
        
        // Attach File Arguments
        $container->getDefinition('ir_media.provider.file')
            ->replaceArgument(5, $config['providers']['file']['allowed_extensions'])
            ->replaceArgument(6, $config['providers']['file']['allowed_mime_types'])
        ;
        
        // Attach Image Arguments
        $container->getDefinition('ir_media.provider.image')
            ->replaceArgument(5, array_map('strtolower', $config['providers']['image']['allowed_extensions']))
            ->replaceArgument(6, $config['providers']['image']['allowed_mime_types'])
            ->replaceArgument(7, new Reference($config['providers']['image']['adapter']))
        ;       
    }    
}
