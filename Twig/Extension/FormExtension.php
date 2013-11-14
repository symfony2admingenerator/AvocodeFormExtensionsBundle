<?php

namespace Avocode\FormExtensionsBundle\Twig\Extension;

use Symfony\Component\Form\FormView;
use Symfony\Bridge\Twig\Form\TwigRendererInterface;

/**
 * FormExtension extends Twig with form capabilities.
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class FormExtension extends \Twig_Extension
{
    /**
     * This property is public so that it can be accessed directly from compiled
     * templates without having to call a getter, which slightly decreases performance.
     *
     * @var \Symfony\Component\Form\FormRendererInterface
     */
    public $renderer;

    public function __construct(TwigRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'afe_form_javascript' => new \Twig_Function_Method($this, 'renderJavascript', array('is_safe' => array('html'))),
            'afe_form_stylesheet' => new \Twig_Function_Method($this, 'renderStylesheet', array('is_safe' => array('html'))),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            'e4js'  =>  new \Twig_Filter_Method($this, 'export_for_js'),
        );
    }

    /**
     * Export variable for javascript
     *
     * @param string $var
     * @return string
     */
    public function export_for_js($var)
    {
        $functionPattern = "%^\\s*function\\s*\\(%is";
        $jsonPattern = "%^\\s*\\{.*\\}\\s*$%is";
        $arrayPattern = "%^\\s*\\[.*\\]\\s*$%is";

        if (is_bool($var)) {
            return $var ? 'true' : 'false';
        }

        if (is_null($var)) {
            return 'null';
        }

        if ('undefined' === $var) {
            return 'undefined';
        }

        if (is_string($var) && !preg_match($functionPattern, $var) && !preg_match($jsonPattern, $var) && !preg_match($arrayPattern, $var)) {
            return '"'.$var.'"';
        }

        if (is_array($var)) {
            $is_assoc = function ($array) {
                return (bool)count(array_filter(array_keys($array), 'is_string'));
            };

            if ($is_assoc($var)) {
                $items = array();
                foreach($var as $key => $val) {
                    $items[] = '"'.$key.'": '.$this->export_for_js($val);
                }
                return '{'.implode(',', $items).'}';
            } else {
                $items = array();
                foreach($var as $val) {
                    $items[] = $this->export_for_js($val);
                }
                return '['.implode(',', $items).']';
            }
        }

        return $var;
    }

    /**
     * Render Function Form Javascript
     *
     * @param FormView $view
     * @param bool $prototype
     *
     * @return string
     */
    public function renderJavascript(FormView $view, $prototype = false)
    {
        $block = $prototype ? 'javascript_prototype' : 'javascript';

        return $this->renderer->searchAndRenderBlock($view, 'afe_' . $block);
    }

    /**
     * Render Function Form Stylesheet
     * @param FormView $view
     *
     * @return string
     */
    public function renderStylesheet(FormView $view)
    {
        return $this->renderer->searchAndRenderBlock($view, 'afe_stylesheet');
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'avocode.twig.extension.form';
    }
}
