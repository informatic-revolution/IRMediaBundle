<?php

namespace IR\MediaBundle\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use IR\MediaBundle\Model\MediaInterface;
use IR\MediaBundle\Manager\MediaManager as AbstractMediaManager;

/**
 * Doctrine MediaManager.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class MediaManager extends AbstractMediaManager
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */          
    protected $objectManager;
    
    /**
     * @var \Doctrine\ORM\EntityRepository
     */           
    protected $repository;    
    
    /**
     * @var string
     */           
    protected $class;  

    
   /**
    * Constructor.
    *
    * @param \Doctrine\Common\Persistence\ObjectManager $om
    * @param string                                     $class
    */        
    public function __construct(ObjectManager $om, $class)
    {        
        $this->objectManager = $om;
        $this->repository    = $om->getRepository($class);
        $this->class         = $om->getClassMetadata($class)->getName();
    }  
    
    /**
     * {@inheritDoc}
     */    
    public function updateMedia(MediaInterface $media, $andFlush = true)
    {  
        $this->objectManager->persist($media);
        
        if ($andFlush) {
            $this->objectManager->flush();
        }     
    }
    
    /**
     * {@inheritDoc}
     */     
    public function deleteMedia(MediaInterface $media)
    {
        $this->objectManager->remove($media);
        $this->objectManager->flush();      
    }  

    /**
     * {@inheritDoc}
     */
    public function findMediaBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }       
    
    /**
     * {@inheritDoc}
     */    
    public function getClass()
    {
        return $this->class;
    }
}
