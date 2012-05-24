<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * (c) Nicolas Bastien nbastien@prestaconcept.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrestaCMS\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PrestaCMS\CoreBundle\Entity\BasePageUrlRewrite
 * 
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien nbastien@prestaconcept.net
 */
abstract class BasePageUrlRewrite
{
    /**
     * @var string $url
     */
    protected $url;

    /**
     * @var Application\PrestaCMS\CoreBundle\Entity\Page
     */
    protected $page;

    /**
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    protected $locale;
    
    /**
     * Set locale
     *
     * @param  string $locale
     * @return BasePageUrlRewrite
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }
    
    /**
     * Set url
     *
     * @param string $url
     * @return BasePageUrlRewrite
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set page
     *
     * @param Application\PrestaCMS\CoreBundle\Entity\Page $page
     * @return BasePageUrlRewrite
     */
    public function setPage(\Application\PrestaCMS\CoreBundle\Entity\Page $page = null)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * Get page
     *
     * @return Application\PrestaCMS\CoreBundle\Entity\Page 
     */
    public function getPage()
    {
        return $this->page;
    }
}