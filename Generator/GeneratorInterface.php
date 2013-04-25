<?php

namespace IR\MediaBundle\Generator;

use IR\MediaBundle\Model\MediaInterface;

/**
 * Interface to be implemented by all Generators.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
interface GeneratorInterface
{

    /**
     * @abstract
     *
     * @param \IR\MediaBundle\Model\MediaInterface $media
     *
     * @return string
     */
    function generatePath(MediaInterface $media);
}