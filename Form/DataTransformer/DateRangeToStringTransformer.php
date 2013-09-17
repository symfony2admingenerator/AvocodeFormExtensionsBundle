<?php

namespace Avocode\FormExtensionsBundle\Form\DataTransformer;

use Avocode\FormExtensionsBundle\Form\Model\DateRange;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
class DateRangeToStringTransformer implements DataTransformerInterface
{
    protected $dateSeparator;

    /**
     * @param string $dateSeparator DateRange separator for the string representation
     */
    public function __construct($dateSeparator = ' - ')
    {
        $this->dateSeparator = $dateSeparator;
    }

    /**
     * Transforms a DateRange into a string.
     *
     * @param DateRange $value DateRange value.
     * @return string string value.
     */
    public function transform($value)
    {
        if ($value) {
            $from = '';
            if ($value->getFrom()) {
                $from = $value->getFrom()->format('Y-m-d');
            }

            $to = '';
            if ($value->getTo()) {
                $to = $value->getTo()->format('Y-m-d');
            }

            return sprintf(
                '%s%s%s',
                $from,
                $this->dateSeparator,
                $to
            );
        }
    }

    /**
     * Transforms a string into a DateRange.
     *
     * @param string $value String value.
     * @return DateRange DateRange value.
     */
    public function reverseTransform($value)
    {
        $parts = explode($this->dateSeparator, $value);

        if ($parts[0] === $value) {
            $parts = array();
        }

        $from = isset($parts[0]) ? $parts[0] : null;
        $to = isset($parts[1]) ? $parts[1] : null;

        return new DateRange($from, $to);
    }
}
