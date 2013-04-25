<?php

namespace IR\MediaBundle\Thumbnail;

use IR\MediaBundle\Model\MediaInterface;
use IR\MediaBundle\Provider\Pool;
use IR\MediaBundle\Provider\MediaProviderInterface;

/**
 * Format Thumbnail.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class FormatThumbnail implements ThumbnailInterface
{
    /**
     * @var string $defaultFormat
     */    
    protected $defaultFormat;

    /**
     * @var \IR\MediaBundle\Provider\Pool $pool
     */     
    protected $pool;
    
    /**
     * Constructor.
     * 
     * @param string $defaultFormat
     */
    public function __construct($defaultFormat, Pool $pool)
    {
        $this->defaultFormat = $defaultFormat;
        $this->pool = $pool;
    }
    
    /**
     * {@inheritdoc}
     */
    public function generatePublicUrl(MediaProviderInterface $provider, MediaInterface $media, $format)
    {
        if ($format == 'reference') {
            $path = $provider->getReferenceImage($media);
        } else {
            $path = sprintf('%s/thumb_%s_%s.%s', $provider->generatePath($media), $media->getId(), $format, $this->getExtension($media));
        }

        return $path;
    }    
    
    /**
     * {@inheritdoc}
     */
    public function generatePrivateUrl(MediaProviderInterface $provider, MediaInterface $media, $format)
    {
        return sprintf('%s/thumb_%s_%s.%s',
            $provider->generatePath($media),
            $media->getId(),
            $format,
            $this->getExtension($media)
        );
    }    
    
    /**
     * {@inheritdoc}
     */
    public function generate(MediaProviderInterface $provider, MediaInterface $media)
    {
        if (!$provider->requireThumbnails()) {
            return;
        }
        
        if (!$this->pool->hasContext($media->getContext())) {
            return;
        }
        
        $referenceFile = $provider->getReferenceFile($media);
        
        $context =  $this->pool->getContext($media->getContext()); 
        $formats = $context['formats'];
        
        foreach ($formats as $format => $settings) {
            $provider->getResizer()->resize(
                $media,
                $referenceFile,
                $provider->getFilesystem()->get($provider->generatePrivateUrl($media, $format), true),
                $this->getExtension($media),
                $settings
            );
        }
    } 
    
    /**
     * @param \IR\MediaBundle\Model\MediaInterface $media
     *
     * @return string the file extension for the $media, or the $defaultExtension if not available
     */
    protected function getExtension(MediaInterface $media)
    {
        $ext = $media->getExtension();
        if (!is_string($ext) || strlen($ext) < 3) {
            $ext = $this->defaultFormat;
        }

        return $ext;
    }
    
    /**
* {@inheritdoc}
*/
    public function delete(MediaProviderInterface $provider, MediaInterface $media)
    {
        
        $context =  $this->pool->getContext($media->getContext()); 
        $formats = $context['formats'];
        
        // delete the differents formats
        foreach ($formats as $format => $definition) {
            $path = $provider->generatePrivateUrl($media, $format);
            
            if ($provider->getFilesystem()->has($path)) {
                $provider->getFilesystem()->delete($path);
            }
        }
    }    
}
