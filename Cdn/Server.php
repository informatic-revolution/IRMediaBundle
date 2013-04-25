<?php

namespace IR\MediaBundle\Cdn;

/**
 * Server Cdn.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class Server implements CdnInterface
{
    /**
     * @var string $path
     */    
    protected $path;

    /**
     * Constructor.
     * 
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * {@inheritDoc}
     */
    public function getPath($relativePath)
    {
        return sprintf('%s/%s', $this->path, $relativePath);
    }
}