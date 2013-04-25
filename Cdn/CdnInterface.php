<?php

namespace IR\MediaBundle\Cdn;

/**
 * Interface to be implemented by all Cdns.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
interface CdnInterface
{
    /**
     * Returns the base path
     *
     * @param string $relativePath
     *
     * @return string
     */
    function getPath($relativePath);
}