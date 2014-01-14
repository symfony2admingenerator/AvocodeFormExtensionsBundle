<?php

namespace Avocode\FormExtensionsBundle\Form\DataTransformer;

use Avocode\FormExtensionsBundle\Form\Model\DateRange;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * @author Stéphane Escandell <stephane.escandell@gmail.com>
 */
class DateRangeToArrayTransformer implements DataTransformerInterface
{
    protected $format;

    /**
     * @param string $dateSeparator DateRange separator for the string representation
     */
    public function __construct($format = 'Y-m-d')
    {
        $this->format = $format;
    }

    /**
     * Transforms a DateRange into an array.
     *
     * @param DateRange $value DateRange value.
     * @return array
     */
    public function transform($value)
    {
        $result = array('from' => null, 'to' => null);

        if ($value instanceof DateRange) {
            if ($value->getFrom()) {
                $result['from'] = $value->getFrom()->format($this->format);
            }

            if ($value->getTo()) {
                $result['to']  = $value->getTo()->format($this->format);
            }
        }

        return $result;
    }

    /**
     * Transforms an array into a DateRange.
     *
     * @param array $value
     * @return DateRange
     */
    public function reverseTransform($value)
    {
        if (!is_array($value)) {
            $value = array();
        }

        return new DateRange(
            array_key_exists('from', $value) ? $value['from'] : null,
            array_key_exists('to', $value) ? $value['to'] : null
        );
    }
}
