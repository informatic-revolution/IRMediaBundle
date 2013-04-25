<?php

namespace IR\MediaBundle\Resizer;

use Gaufrette\File;
use IR\MediaBundle\Model\MediaInterface;

/**
 * Interface to be implemented by all Resizers.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
interface ResizerInterface
{
    /**
     * @param \IR\MediaBundle\Model\MediaInterface $media
     * @param \Gaufrette\File                      $in
     * @param \Gaufrette\File                      $out
     * @param string                               $format
     * @param array                                $settings
     * 
     * @return void
     */
    function resize(MediaInterface $media, File $in, File $out, $format, array $settings);

    /**
     * @param \IR\MediaBundle\Model\MediaInterface $media
     * @param array                                $settings
     * 
     * @return \Imagine\Image\Box
     */
    function getBox(MediaInterface $media, array $settings);
}
