<?php

namespace Avocode\FormExtensionsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

/**
 * See `Resources/doc/elastic-textarea/overview.md` for documentation
 *
 * @author Pierrick VIGNAND <pierrick.vignand@gmail.com>
 */
class ElasticTextareaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'textarea';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'afe_elastic_textarea';
    }
}
