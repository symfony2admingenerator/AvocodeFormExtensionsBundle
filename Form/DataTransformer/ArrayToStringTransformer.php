<?php

namespace Avocode\FormExtensionsBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

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
     * {@inheritdoc}
     */
    public function transform($array)
    {
        if (null === $array || !is_array($array)) {
            return '';
        }

        return implode($this->separator, $array);
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($string)
    {
        if (is_array($string)) {
            return $string;
        }

        $transformedString = explode($this->separator, $string);

        if (!empty($this->keys)) {
            return array_combine($this->keys, $transformedString);
        }

        return $transformedString;
    }
}
