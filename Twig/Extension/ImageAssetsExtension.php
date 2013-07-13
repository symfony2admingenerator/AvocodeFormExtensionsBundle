<?php

namespace Avocode\FormExtensionsBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * This extension adds common twig filter for various image manipulation 
 * bundles. Depending on %avocode.form.image_manipulator% setting a diffrent 
 * image manipulation bundle is used.
 * 
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class ImageFilterExtension extends \Twig_Extension
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'image_filter' => new \Twig_Function_Method($this, 'filter'),
        );
    }

    /**
     * Gets the browser path for the image and filter to apply.
     *
     * @return string
     */
    public function filter()
    {
        $params = func_get_args();
        
        if ('liip_imagine' === $this->getThumbnailGenerator()) {
            $ext = $this->container->get('liip_imagine.twig.extension'); 
            return call_user_func_array(array($ext, "filter"), $params);
        }
        
        if ('avalanche_imagine' === $this->getThumbnailGenerator()) {
            $ext = $this->container->get('imagine.twig.extension'); 
            return call_user_func_array(array($ext, "applyFilter"), $params);
        }
        
        return $params[0];
    }

    /**
     * Get thumbnail generator name
     *
     * @return string Parameter value
     */
    public function getThumbnailGenerator()
    {
        return $this->container->getParameter('avocode.form.image_manipulator');
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'avocode.twig.extension.image_filter';
    }
}
