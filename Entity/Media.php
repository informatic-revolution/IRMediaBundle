<?php

namespace IR\MediaBundle\Entity;

use IR\MediaBundle\Model\Media as AbstractMedia;

/**
 * Abstract Media Entity.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
abstract class Media extends AbstractMedia
{
    public function prePersist()
    {   
        $this->createdAt = new \DateTime();
    }

    public function preUpdate()
    {   
        $this->updatedAt = new \DateTime();
    }    
}