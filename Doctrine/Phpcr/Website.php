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

use Presta\CMSCoreBundle\Model\Website as WebsiteModel;
use Sonata\TranslationBundle\Model\Phpcr\TranslatableInterface;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class Website extends WebsiteModel implements TranslatableInterface
{
    const WEBSITE_PREFIX    = 'website';
    const THEME_PREFIX      = 'theme';
    const ROUTE_PREFIX      = 'route';
    const MENU_PREFIX       = 'menu';
    const PAGE_PREFIX       = 'page';

    /**
     * @return string
     */
    public function getId()
    {
        return $this->getPath();
    }

    /**
     * @param $path
     */
    public function setPath($path)
    {
        $this->id = $path;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getRoutePrefix()
    {
        return $this->getPath() . '/' . self::ROUTE_PREFIX . '/' . $this->getLocale();
    }

    /**
     * @return string
     */
    public function getMenuRoot()
    {
        return $this->getPath() . '/' . self::MENU_PREFIX;
    }

    /**
     * @return string
     */
    public function getPageRoot()
    {
        return $this->getPath() . '/' . self::PAGE_PREFIX;
    }
}
