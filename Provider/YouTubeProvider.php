<?php

namespace IR\MediaBundle\Provider;

use IR\MediaBundle\Model\MediaInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Youtube provider
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class YouTubeProvider extends BaseVideoProvider
{
    /**
     * {@inheritdoc}
     */
    protected function fixBinaryContent(MediaInterface $media)
    {
        if (!$media->getBinaryContent()) {
            return;
        }

        if (preg_match("/(?<=v(\=|\/))([-a-zA-Z0-9_]+)|(?<=youtu\.be\/)([-a-zA-Z0-9_]+)/", $media->getBinaryContent(), $matches)) {
            $media->setBinaryContent($matches[2]);
        }
    }    
    
    /**
     * {@inheritdoc}
     */
    protected function doTransform(MediaInterface $media)
    {
        $this->fixBinaryContent($media);

        if (!$media->getBinaryContent()) {
            return;
        }

        $media->setProviderReference($media->getBinaryContent());

        $this->updateMetadata($media, true);
    } 
    
    /**
     * {@inheritdoc}
     */
    public function updateMetadata(MediaInterface $media, $force = false)
    {
        $url = sprintf('http://www.youtube.com/oembed?url=http://www.youtube.com/watch?v=%s&format=json', $media->getProviderReference());

        try {
            $metadata = $this->getMetadata($media, $url);
        } catch (\RuntimeException $e) {
            //$media->setEnabled(false);
            //$media->setProviderStatus(MediaInterface::STATUS_ERROR);

            return;
        }

        $media->setProviderMetadata($metadata);

        $media->setName($metadata['title']);
        $media->setAuthorName($metadata['author_name']);        
        $media->setHeight($metadata['height']);
        $media->setWidth($metadata['width']);
        $media->setContentType('video/x-flv');
    }    
}
