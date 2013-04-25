<?php

namespace IR\MediaBundle\Generator;

use IR\MediaBundle\Model\MediaInterface;

/**
 * Default Generator.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class DefaultGenerator implements GeneratorInterface
{
    
    /**
     * @var int $firstLevel
     */    
    protected $firstLevel;

    /**
     * @var int $secondLevel
     */      
    protected $secondLevel;

    /**
     * Constructor.
     * 
     * @param int $firstLevel
     * @param int $secondLevel
     */
    public function __construct($firstLevel = 100000, $secondLevel = 1000)
    {
        $this->firstLevel = $firstLevel;
        $this->secondLevel = $secondLevel;
    }
    
    /**
     * {@inheritdoc}
     */
    public function generatePath(MediaInterface $media)
    {
        $rep_first_level = (int) ($media->getId() / $this->firstLevel);
        $rep_second_level = (int) (($media->getId() - ($rep_first_level * $this->firstLevel)) / $this->secondLevel);

        return sprintf('%s/%04s/%02s', $media->getContext(), $rep_first_level + 1, $rep_second_level + 1);
    }    
}