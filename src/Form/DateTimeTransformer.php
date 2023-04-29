<?php

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Validator\Constraints\Date;

class DateTimeTransformer implements DataTransformerInterface
{
    private $format;

    public function __construct($format = 'Y-m-d')
    {
        $this->format = $format;
    }

    public function transform($value)
    {
        if (null === $value) {
            return '';
        }

        return $value->format($this->format);
    }

    public function reverseTransform($value)
    {
        if (!$value) {
            return null;
        }

        try {
            $date = \DateTime::createFromFormat($this->format, $value);
        } catch (\Exception $e) {
            throw new TransformationFailedException(sprintf('Unable to transform "%s" into a valid date/time object', $value));
        }

        if (!$date instanceof \DateTime) {
            throw new TransformationFailedException(sprintf('"%s" is not a valid date/time object', $value));
        }

        return $date;
    }
}
