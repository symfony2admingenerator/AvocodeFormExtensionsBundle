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
            'form_javascript' => new \Twig_Function_Method($this, 'renderJavascript', array('is_safe' => array('html'))),
            'form_stylesheet' => new \Twig_Function_Node('Symfony\Bridge\Twig\Node\SearchAndRenderBlockNode', array('is_safe' => array('html'))),
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
        $functionPattern = "%^\\s*function\\s*\\(%i";
        
        if (is_bool($var)) {
            return $var ? '1' : '0';
        }
        
        if (is_null($var)) {
            return 'null';
        }
        
        if ('undefined' === $var) {
            return 'undefined';
        }
        
        if (is_string($var) && false === preg_match($functionPattern, $var)) {
            return '"'.$var.'"';
        }
        
        if (is_array($var)) {
            $functions = array();     // array to store function strings
            $replacements = array();  // array to store replacement keys
            
            foreach ($var as $key => &$value) {
                // search for values starting with "function("
                if (preg_match($functionPattern, $value)) {
                    $functions[] = $value;
                    $replacements[] = '"%'.$key.'%"';
                }
            }
            
            return str_replace($replacements, $functions, json_encode($var));
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

        return $this->renderer->searchAndRenderBlock($view, $block);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'avocode.twig.extension.form';
    }
}
