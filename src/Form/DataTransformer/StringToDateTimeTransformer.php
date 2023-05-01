<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class StringToDateTimeTransformer implements DataTransformerInterface
{
    public function transform($dateTime)
    {
        if ($dateTime === null) {
            return '';
        }

        return $dateTime->format('Y-m-d');
    }

    public function reverseTransform($dateString)
    {
        if (!$dateString) {
            return;
        }

        return \DateTime::createFromFormat('Y-m-d', $dateString);
    }
}
