<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Doctrine\Phpcr;

use Presta\CMSCoreBundle\Model\Block as BlockModel;
use Symfony\Cmf\Bundle\CoreBundle\Translatable\TranslatableInterface;
use Symfony\Component\Validator\ExecutionContext;

/**
 * BaseBlock Model
 *
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 * */
class Block extends BlockModel implements TranslatableInterface
{

    /**
     * Validate settings
     *
     * @param \Symfony\Component\Validator\ExecutionContext $context
     */
    public function isSettingsValid(ExecutionContext $context)
    {
        foreach ($this->getSettings() as $value) {
            if (is_array($value)) {
                $context->addViolationAt('settings', 'A multidimensional array is not allowed, only use key-value pairs.');
            }
        }
    }

    /**
     * Returns the path of the website content root
     *
     * used by form builder when linking to a content
     *
     * @return
     */
    public function getContentRoot()
    {
        $path = $this->getId();
        if (strpos($path, '/page/') === false) {
            if (strpos($path, '/theme/') !== false) {
                return substr($path, 0, strpos($path, '/theme/')) . '/page';
            }

            return null;
        }

        return substr($path, 0, strpos($path, '/page/') + 5);
    }

    /**
     * {@inheritdoc}
     */
    public function getSettings()
    {
        if (!is_array($this->settings)) {
            //If translation is not created yet, PHPCR-ODM return null
            return array();
        }

        return $this->settings;
    }
}
