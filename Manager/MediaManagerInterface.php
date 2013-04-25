<?php

namespace IR\MediaBundle\Manager;

use IR\MediaBundle\Model\MediaInterface;

/**
 * Interface to be implemented by all Media Managers.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
interface MediaManagerInterface
{
    /**
     * Create an empty media instance.
     * 
     * @return \IR\MediaBundle\Model\MediaInterface
     */    
    public function createMedia();
    
    /**
     * Update a media.
     *
     * @param \IR\MediaBundle\Model\MediaInterface $media
     */
    public function updateMedia(MediaInterface $media);
    
    /**
     * Delete a product.
     *
     * @param \IR\MediaBundle\Model\MediaInterface $media
     */
    public function deleteMedia(MediaInterface $media);    

    /**
     * Find one media by the given criteria.
     *
     * @param array $criteria
     * 
     * @return \IR\MediaBundle\Model\MediaInterface
     */
    public function findMediaBy(array $criteria);    
    
    /**
     * Find a media by id.
     *
     * @param integer $id
     *
     * @return \IR\MediaBundle\Model\MediaInterface
     */
    public function findMediaById($id);      
    
    /**
     * Returns the media's fully qualified class name.
     *
     * @return string
     */
    public function getClass();     
}

