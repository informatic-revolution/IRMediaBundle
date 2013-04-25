<?php

namespace IR\MediaBundle\Provider;

use Gaufrette\Filesystem;
use IR\MediaBundle\Model\MediaInterface;
use IR\MediaBundle\Cdn\CdnInterface;
use IR\MediaBundle\Generator\GeneratorInterface;
use Buzz\Browser;
use IR\MediaBundle\Thumbnail\ThumbnailInterface;

/**
 * Base video provider
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
abstract class BaseVideoProvider extends BaseProvider
{
    protected $browser;
    protected $metadata;


    /**
     * @param string $name
     * @param \Gaufrette\Filesystem $filesystem
     * @param \IR\MediaBundle\CDN\CDNInterface $cdn
     * @param \IR\MediaBundle\Generator\GeneratorInterface $pathGenerator
     * @param \IR\MediaBundle\Thumbnail\ThumbnailInterface $thumbnail
     * @param \Buzz\Browser $browser
     */
    public function __construct($name, Filesystem $filesystem, CDNInterface $cdn, GeneratorInterface $pathGenerator, ThumbnailInterface $thumbnail, Browser $browser)
    {
        parent::__construct($name, $filesystem, $cdn, $pathGenerator, $thumbnail);

        $this->browser = $browser;
    }    
    
    /**
     * {@inheritdoc}
     */
    public function getReferenceImage(MediaInterface $media)
    {
        return $media->getMetadataValue('thumbnail_url');
    }
    
    /**
     * {@inheritdoc}
     */
    public function getReferenceFile(MediaInterface $media)
    {
        $key = $this->generatePrivateUrl($media, 'reference');

        // the reference file is remote, get it and store it with the 'reference' format
        if ($this->getFilesystem()->has($key)) {
            $referenceFile = $this->getFilesystem()->get($key);
        } else {
            $referenceFile = $this->getFilesystem()->get($key, true);
            $metadata = $this->metadata ? $this->metadata->get($media, $referenceFile->getName()) : array();
            $referenceFile->setContent(file_get_contents($this->getReferenceImage($media)), $metadata);
        }

        return $referenceFile;
    }    
    
     /**
     * {@inheritdoc}
     */
    public function generatePublicUrl(MediaInterface $media, $format)
    {
        return $this->getCdn()->getPath(sprintf('%s/thumb_%d_%s.jpg',
            $this->generatePath($media),
            $media->getId(),
            $format
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function generatePrivateUrl(MediaInterface $media, $format)
    {
        return sprintf('%s/thumb_%d_%s.jpg',
            $this->generatePath($media),
            $media->getId(),
            $format
        );
    }   
    
    /**
     * @throws \RuntimeException
     *
     * @param \IR\MediaBundle\Model\MediaInterface $media
     * @param string $url
     *
     * @return mixed
     */
    protected function getMetadata(MediaInterface $media, $url)
    {
        try {
            $response = $this->browser->get($url);
        } catch (\RuntimeException $e) {
            throw new \RuntimeException('Unable to retrieve the video information for :' . $url, null, $e);
        }

        $metadata = json_decode($response->getContent(), true);

        if (!$metadata) {
            throw new \RuntimeException('Unable to decode the video information for :' . $url);
        }

        return $metadata;
    }  
    
    /**
     * {@inheritdoc}
     */
    public function postUpdate(MediaInterface $media)
    {
        $this->postPersist($media);
    }
    
    /**
     * {@inheritdoc}
     */
    public function postPersist(MediaInterface $media)
    {
        if (!$media->getBinaryContent()) {
            return;
        }

        $this->generateThumbnails($media);
    }    
}