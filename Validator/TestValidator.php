<?php

namespace IR\MediaBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use IR\MediaBundle\Provider\Pool;

/**
 * Media Validator.
 *
 * @author Julien Kirsch <informatic.revolution@gmail.com>
 */
class TestValidator extends ConstraintValidator
{

    public function isValid($entity, Constraint $constraint) 
    {           /*
        if (!($entity instanceof MediaInterface)) {
             throw new UnexpectedTypeException($entity, 'IR\MediaBundle\Model\MediaInterface');           
        }*/
        
        
        
        $this->context->addViolation($constraint->message, array(), '');
          exit(); 
        
        // all true, we added the violation already!
        return false;
    }
}
