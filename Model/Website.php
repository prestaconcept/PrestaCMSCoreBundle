<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Model;

use Sonata\TranslationBundle\Traits\Translatable;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class Website extends AbstractModel
{
    use Translatable;

    /**
     * @var string $theme
     */
    protected $theme;

    /**
     * Attribute use to render website main menu in admin
     * @var array
     */
    protected $mainMenuChildren;

    /**
     * @var array $availableLocales
     */
    protected $availableLocales;

    public function __construct()
    {
        $this->availableLocales = array();
        $this->mainMenuChildren = null;
    }

    /**
     * @param string $theme
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
    }

    /**
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * @param array $availableLocales
     */
    public function setAvailableLocales($availableLocales)
    {
        $this->availableLocales = $availableLocales;
    }

    /**
     * @return array
     */
    public function getAvailableLocales()
    {
        return $this->availableLocales;
    }

    /**
     * @return array
     * @throws \BadMethodCallException
     */
    public function getMainMenuChildren()
    {
        if ($this->mainMenuChildren === null) {
            throw new \BadMethodCallException();
        }

        return $this->mainMenuChildren;
    }

    /**
     * @param array $mainMenuChildren
     */
    public function setMainMenuChildren(array $mainMenuChildren)
    {
        $this->mainMenuChildren = $mainMenuChildren;
    }

    /**
     * @return string
     */
    public function getMainMenuRootPath()
    {
        return $this->getMenuRoot() . '/main';
    }
}
