<?php

namespace IR\MediaBundle\Manager;

/**
 * Abstract Media Manager implementation..
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
abstract class MediaManager implements MediaManagerInterface
{
    /**
     * {@inheritDoc}
     */    
    public function createMedia()
    {
        $class = $this->getClass();
        $media = new $class();
        
        return $media;
    }
    
    /**
     * {@inheritDoc}
     */    
    public function findMediaById($id)
    {
        return $this->findMediaBy(array('id' => $id));
    }       
}
