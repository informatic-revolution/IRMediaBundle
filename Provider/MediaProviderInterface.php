<?php

namespace IR\MediaBundle\Provider;

use IR\MediaBundle\Model\MediaInterface;
use IR\MediaBundle\Resizer\ResizerInterface;

/**
 * Interface to be implemented by media providers.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
interface MediaProviderInterface
{
    /**
     * Gets name
     * 
     * @return string
     */
    function getName(); 
    
    /**
     * Gets filesystem
     * 
     * @return \Gaufrette\Filesystem
     */
    function getFilesystem();     

    /**
     * Gets cdn
     * 
     * @return \IR\MediaBundle\CDN\CDNInterface
     */
    function getCdn();   
    
    /**
     * Gets resizer
     * 
     * @return \IR\MediaBundle\Media\ResizerInterface
     */
    function getResizer();

    /**
     * Gets a template by name
     * 
     * @param string $name
     *
     * @return string
     */
    function getTemplate($name);     
    
    /**
     * Sets name
     * 
     * @param string $name
     */
    function setName($name);    
    
    /**
     * Sets resizer
     * 
     * @return \IR\MediaBundle\Media\ResizerInterface
     */
    public function setResizer(ResizerInterface $resizer);    
    
    /**
     * Sets templates
     * 
     * @param array $templates
     */
    function setTemplates(array $templates);
 
    /**
     * @param \IR\MediaBundle\Model\MediaInterface $media
     *
     * @return void
     */
    function transform(MediaInterface $media);
    
    /**
     * Generates the media path
     *
     * @param \IR\MediaBundle\Model\MediaInterface $media
     *
     * @return string
     */
    function generatePath(MediaInterface $media);    
    
    /**
     * return the reference image of the media, can be the video thumbnail or the original uploaded picture
     *
     * @param \IR\MediaBundle\Model\MediaInterface $media
     *
     * @return string to the reference image
     */
    function getReferenceImage(MediaInterface $media); 
    
    /**
     * @param \IR\MediaBundle\Model\MediaInterface $media
     *
     * @return \Gaufrette\File
     */
    function getReferenceFile(MediaInterface $media);    
    
    /**
     * Generate the public path
     *
     * @param \IR\MediaBundle\Model\MediaInterface $media
     * @param string                               $format
     *
     * @return string
     */
    function generatePublicUrl(MediaInterface $media, $format);    
    
    /**
     * Generate the private path
     *
     * @param \IR\MediaBundle\Model\MediaInterface $media
     * @param string                               $format
     *
     * @return string
     */
    function generatePrivateUrl(MediaInterface $media, $format);    
    
    /**
     * return true if the media related to the provider required thumbnails (generation)
     *
     * @return boolean
     */
    function requireThumbnails();      
    
    /**
     * Generated thumbnails linked to the media, a thumbnail is a format used on the website
     *
     * @param \IR\MediaBundle\Model\MediaInterface $media
     *
     * @return void
     */
    function generateThumbnails(MediaInterface $media);    
    
    /**
     *
     * @param \IR\MediaBundle\Model\MediaInterface $media
     *
     * @return void
     */
    function postPersist(MediaInterface $media);
}