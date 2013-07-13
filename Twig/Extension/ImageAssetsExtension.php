<?php

namespace Avocode\FormExtensionsBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * This extension adds common twig function for various asset provider 
 * bundles and common twig filter image manipulation bundles. 
 * 
 * Depending on %avocode.form.asset_provider% setting a diffrent 
 * asset provider bundle is used.
 * 
 * Depending on %avocode.form.image_manipulator% setting a diffrent 
 * image manipulation bundle is used.
 * 
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class ImageAssetsExtension extends \Twig_Extension
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
            'image_asset'   =>  new \Twig_Function_Method($this, 'asset'),
        );
    }

    public function getFilters()
    {
        return array(
            'image_filter'  =>  new \Twig_Filter_Method($this, 'filter'),
        );
    }
    
    /**
     * Gets the browser path for the image and filter to apply.
     *
     * @return string The public path.
     */
    public function asset($object, $field)
    {
        $params = func_get_args();
        
        if ('vich_uploader' === $this->getAssetProvider()) {
            $ext = $this->container->get('vich_uploader.twig.extension.uploader'); 
            return call_user_func_array(array($ext, "asset"), $params);
        }
        
        // In case no asset provider is used we expect object to have
        // a special method returning file's path
        $getter = "get".ucfirst($field)."Path";
        
        return $object->$getter();
    }

    /**
     * Gets the browser path for the image and filter to apply
     *
     * @return string
     */
    public function filter()
    {
        $params = func_get_args();
        $path = $params[0];
        
        if ('liip_imagine' === $this->getImageManipulator()) {
            $ext = $this->container->get('liip_imagine.twig.extension'); 
            return call_user_func_array(array($ext, "filter"), $params);
        }
        
        if ('avalanche_imagine' === $this->getImageManipulator()) {
            $ext = $this->container->get('imagine.twig.extension'); 
            return call_user_func_array(array($ext, "applyFilter"), $params);
        }
        
        // In case no image manipulator is used we
        // return the unmodified path
        return $path;
    }

    /**
     * Get asset provider name
     *
     * @return string|null Parameter value
     */
    public function getAssetProvider()
    {
        return $this->container->getParameter('avocode.form.asset_provider');
    }

    /**
     * Get image manipulator name
     *
     * @return string|null Parameter value
     */
    public function getImageManipulator()
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
