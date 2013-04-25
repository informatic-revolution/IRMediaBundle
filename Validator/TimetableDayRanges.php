<?php

namespace IR\MediaBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @api
 */
class TimetableDayRanges extends Constraint
{
    public $message = 'timetable.ranges.invalid';
    
    public function validatedBy()
    {   
        return 'irerp_hr_employee.validator.timetable_day_ranges';
    }
        
    /**
     * {@inheritDoc}
     */
    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }    
}