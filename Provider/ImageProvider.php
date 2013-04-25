<?php

namespace IR\MediaBundle\Provider;

use Gaufrette\Filesystem;
use Imagine\Image\ImagineInterface;
use IR\MediaBundle\Model\MediaInterface;
use IR\MediaBundle\CDN\CDNInterface;
use IR\MediaBundle\Generator\GeneratorInterface;
use IR\MediaBundle\Thumbnail\ThumbnailInterface;

/**
 * Image Provider.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class ImageProvider extends FileProvider
{   
    /**
     * @var \Imagine\Image\ImagineInterface $imagineAdapter
     */      
    protected $imagineAdapter;
    
    
    /**
     * Constructors. 
     * 
     * @param \Gaufrette\Filesystem                        $filesystem
     * @param \IR\MediaBundle\CDN\CDNInterface             $cdn
     * @param \IR\MediaBundle\Generator\GeneratorInterface $pathGenerator
     * @param \IR\MediaBundle\Thumbnail\ThumbnailInterface $thumbnail
     * @param array                                        $allowedExtensions
     * @param array                                        $allowedMimeTypes
     * @param \Imagine\Image\ImagineInterface              $adapter
     */    
    public function __construct($name, Filesystem $filesystem, CDNInterface $cdn, GeneratorInterface $pathGenerator, ThumbnailInterface $thumbnail, array $allowedExtensions = array(), array $allowedMimeTypes = array(), ImagineInterface $adapter)
    {
        parent::__construct($name, $filesystem, $cdn, $pathGenerator, $thumbnail, $allowedExtensions, $allowedMimeTypes);

        $this->imagineAdapter = $adapter;
    }
         
    /**
     * {@inheritdoc}
     */
    protected function doTransform(MediaInterface $media)
    {
        parent::doTransform($media);
        
        if ($media->getBinaryContent()) {
            $image = $this->imagineAdapter->open($media->getBinaryContent()->getPathname());
            $size = $image->getSize();

            $media->setWidth($size->getWidth());
            $media->setHeight($size->getHeight());
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function generatePublicUrl(MediaInterface $media, $format)
    {   // TO WORK
        if ($format == 'reference') {
            $path = $this->getReferenceImage($media);
        } else {
            $path = $this->thumbnail->generatePublicUrl($this, $media, $format);
        }
        
        return $this->getCdn()->getPath($path);
    }    
    
    /**
     * {@inheritdoc}
     */
    public function generatePrivateUrl(MediaInterface $media, $format)
    {
        return $this->thumbnail->generatePrivateUrl($this, $media, $format);
    }   
}