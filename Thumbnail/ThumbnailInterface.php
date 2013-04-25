<?php

namespace IR\MediaBundle\Thumbnail;

use IR\MediaBundle\Model\MediaInterface;
use IR\MediaBundle\Provider\MediaProviderInterface;

/**
 * Interface to be implemented by all Thumbnails.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
interface ThumbnailInterface
{
    /**
     * @param \IR\MediaBundle\Provider\MediaProviderInterface $provider
     * @param \IR\MediaBundle\Model\MediaInterface            $media
     * @param string                                          $format
     */
    function generatePublicUrl(MediaProviderInterface $provider, MediaInterface $media, $format);    
    
    /**
     * @param \IR\MediaBundle\Provider\MediaProviderInterface $provider
     * @param \IR\MediaBundle\Model\MediaInterface            $media
     * @param string                                          $format
     */
    function generatePrivateUrl(MediaProviderInterface $provider, MediaInterface $media, $format);    
    
    /**
     * @param \IR\MediaBundle\Provider\MediaProviderInterface $provider
     * @param \IR\MediaBundle\Model\MediaInterface            $media
     */
    function generate(MediaProviderInterface $provider, MediaInterface $media); 
    
    /**
* @param \Sonata\MediaBundle\Provider\MediaProviderInterface $provider
* @param \Sonata\MediaBundle\Model\MediaInterface $media
*/
    function delete(MediaProviderInterface $provider, MediaInterface $media);    
}