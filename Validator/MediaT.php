<?php

namespace IR\MediaBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Media.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class MediaT extends Constraint
{
    public $message = 'timetable.range.invalid';
    
    public function validatedBy()
    {   exit();
        return 'ir_media_v';
    }
        
    /**
     * {@inheritDoc}
     */
    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }    
}