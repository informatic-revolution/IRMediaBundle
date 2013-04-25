<?php

namespace IR\MediaBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use IR\MediaBundle\Model\MediaInterface;
use IR\MediaBundle\Provider\Pool;

class TimetableDayRangesValidator extends ConstraintValidator
{
    
    protected $pool;
    
    public function __construct(Pool $pool)
    {   
        $this->pool = $pool;
    }




    public function isValid($entity, Constraint $constraint) 
    {                   
        if (!($entity instanceof MediaInterface)) {
             throw new UnexpectedTypeException($entity, 'IR\MediaBundle\Model\MediaInterface');           
        }
        
        $provider = $this->pool->getProvider($entity->getProviderName());

        $provider->validate($entity, $this->context);
        
        
        return true;
    }
}
