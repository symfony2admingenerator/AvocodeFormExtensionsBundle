<?php

namespace Avocode\FormExtensionsBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * {@inheritdoc}
 *
 * @author Bilal Amarni <bilal.amarni@gmail.com>
 * @author Stéphane Escandell <stephane.escandell@gmail.com>
 */
class ArrayToStringTransformer implements DataTransformerInterface
{
    /**
     * @var string
     */
    private $separator;

    /**
     * @var array
     */
    private $keys;

    /**
     * Default constructor
     *
     * @param string $glue
     */
    public function __construct($separator = ',', array $keys = array())
    {
        $this->separator = $separator;
        $this->keys = $keys;
    }

    /**
     * Transforms an array to a string
     *
     * {@inheritdoc}
     */
    public function transform($array)
    {
        if (null === $array) {
            return '';
        }

        if (!is_array($array)) {
            throw new TransformationFailedException('Expected an array');
        }

        $array = array_filter(array_values($array), 'strlen');

        return empty($array) ? '' : implode($this->separator, $array);
    }

    /**
     * Transforms a string to an array
     *
     * {@inheritdoc}
     */
    public function reverseTransform($string)
    {
        if (!is_string($string)) {
            throw new TransformationFailedException('Expected a string');
        }

        if ( 0 === strlen($string)) {
            return array();
        }

        $transformedString = explode($this->separator, $string);

        if (!empty($this->keys)) {
            if (count($this->keys) != count($transformedString)) {
                throw new TransformationFailedException('Invalid value format.');
            }

            return array_combine($this->keys, $transformedString);
        }

        return $transformedString;
    }
}
