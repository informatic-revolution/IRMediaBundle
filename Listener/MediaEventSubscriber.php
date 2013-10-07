<?php

namespace IR\MediaBundle\Listener;

use Doctrine\ORM\Events;
use Doctrine\Common\EventArgs;
use Doctrine\Common\EventSubscriber;
use Symfony\Component\DependencyInjection\ContainerInterface;
use IR\MediaBundle\Model\MediaInterface;
use IR\MediaBundle\Provider\Pool;

/**
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class MediaEventSubscriber implements EventSubscriber
{

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface $container
     */    
    private $container;

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }    
    
    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::prePersist,
            Events::preUpdate,
            Events::preRemove,
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove,
        );
    }
    
    /**
     * @return \IR\MediaBundle\Provider\Pool
     */
    public function getPool()
    {
        return $this->container->get('ir_media.pool');
    }    
    
    /**
     * @param  \Doctrine\ORM\Event\LifecycleEventArgs $args
     * @return \IR\MediaBundle\Provider\MediaProviderInterface
     */
    protected function getProvider(EventArgs $args)
    {
        $media = $args->getEntity();

        if (!$media instanceof MediaInterface) {
            return null;
        }

        return $this->getPool()->getProvider($media->getProviderName());
    }    
    
    /**
     * @param \Doctrine\Common\EventArgs $args
     *
     * @return void
     */
    public function prePersist(EventArgs $args)
    {   
        if (!($provider = $this->getProvider($args))) {
            return;
        }

        $provider->transform($args->getEntity());
    }
    
    /**
     * @param  \Doctrine\Common\EventArgs $args
     * @return void
     */
    public function postPersist(EventArgs $args)
    {   
        if (!($provider = $this->getProvider($args))) {
            return;
        }
        
        $provider->postPersist($args->getEntity());
    }
    
    /**
     * @param \Doctrine\Common\EventArgs $args
     *
     * @return void
     */
    public function preUpdate(EventArgs $args)
    {
        if (!($provider = $this->getProvider($args))) {
            return;
        }

        //$provider->transform($args->getEntity());
        $provider->preUpdate($args->getEntity());

        //$this->recomputeSingleEntityChangeSet($args);
    }
    
    /**
     * @param \Doctrine\Common\EventArgs $args
     *
     * @return void
     */
    public function postUpdate(EventArgs $args)
    {   
        if (!($provider = $this->getProvider($args))) {
            return;
        }
        
        $provider->postUpdate($args->getEntity());
    } 
    
    /**
     * @param \Doctrine\Common\EventArgs $args
     *
     * @return void
     */
    public function preRemove(EventArgs $args)
    {
        if (!($provider = $this->getProvider($args))) {
            return;
        }

        $provider->preRemove($args->getEntity());
    }
    
    /**
* @param \Doctrine\Common\EventArgs $args
*
* @return void
*/
    public function postRemove(EventArgs $args)
    {
        if (!($provider = $this->getProvider($args))) {
            return;
        }

        $provider->postRemove($args->getEntity());
    }    
}
