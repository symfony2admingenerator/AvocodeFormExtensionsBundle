<?php

namespace Avocode\FormExtensionsBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
class DateTimeToPartsTransformer implements DataTransformerInterface
{
    /**
     * Transforms a DateTime into an array.
     *
     * @param DateTime $value DateTime value.
     * @return string string value.
     */
    public function transform($value)
    {
        $parts = array(
            'date' => null,
            'time' => null,
        );
        
        if ($value) {
            $parts['date'] = new \DateTime($value->format('Y-m-d'));
            $parts['time'] = new \DateTime($value->format('H:i:s'));
        }
        
        return $parts;
    }

    /**
     * Transforms an array into a DateTime.
     *
     * @param array $value Array value.
     * @return DateTime DateTime value.
     */
    public function reverseTransform($value)
    {
        if ($value['date'] && $value['time']) {
            return new \DateTime(sprintf(
                '%s %s',
                $value['date']->format('Y-m-d'),
                $value['time']->format('H:i:s')
            ));
        }
        
        if ($value['date']) {
            return new \DateTime(sprintf(
                '%s',
                $value['date']->format('Y-m-d')
            ));
        }
        
        if ($value['time']) {
            return new \DateTime(sprintf(
                '%s',
                $value['time']->format('H:i:s')
            ));
        }
        
        return null;
    }
}
