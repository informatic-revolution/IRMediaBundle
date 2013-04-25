<?php

namespace IR\MediaBundle\Metadata;

use IR\MediaBundle\Model\MediaInterface;

interface MetadataBuilderInterface
{

    /**
     * Get metadata for media object
     *
     * @param MediaInterface $media
     * @param string $filename
     *
     * @return array
     */
    public function get(MediaInterface $media, $filename);
}