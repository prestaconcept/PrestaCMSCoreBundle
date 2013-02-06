<?php
/*
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;

use Presta\CMSCoreBundle\Document\Website;
use Presta\CMSCoreBundle\Model\Template;

use Symfony\Cmf\Bundle\BlockBundle\Document\ContainerBlock;

/**
 * Theme Document child of a website : Zone container + configuration
 *
 * @package    Presta
 * @subpackage CMSCoreBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * @PHPCRODM\Document(referenceable=true, repositoryClass="Presta\CMSCoreBundle\Document\Theme\Repository")
 */
class Theme extends ContainerBlock
{
    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $template;

    /**
     * @var string
     */
    protected $screenshot;

    /**
     * @var string
     */
    protected $adminStyle;

    /**
     * @var integer
     */
    protected $cols;

    /**
     * @var array
     */
    protected $zones;

    /**
     * @var array
     */
    protected $pageTemplates;

    /**
     * {@inheritdoc}
     */
    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->zones = array();
        $this->pageTemplates = array();
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Add a zone and initialize its id
     *
     * @return object
     */
    public function addZone($zone)
    {
        return $this->children->set($zone->getName(), $zone);
    }

    /**
     * Alias de getChildren
     *
     * @return object
     */
    public function getZones()
    {
        return $this->getChildren();
    }

    /**
     * @param string $adminStyle
     */
    public function setAdminStyle($adminStyle)
    {
        $this->adminStyle = $adminStyle;
    }

    /**
     * @return string
     */
    public function getAdminStyle()
    {
        return $this->adminStyle;
    }

    /**
     * @param int $cols
     */
    public function setCols($cols)
    {
        $this->cols = $cols;
    }

    /**
     * @return int
     */
    public function getCols()
    {
        return $this->cols;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param array $pageTemplates
     */
    public function setPageTemplates($pageTemplates)
    {
        $this->pageTemplates = $pageTemplates;
    }

    /**
     * @return array
     */
    public function getPageTemplates()
    {
        return $this->pageTemplates;
    }

    /**
     * Add a page template
     *
     * @param  Template                          $template
     * @return \Presta\CMSCoreBundle\Model\Theme
     */
    public function addPageTemplate(Template $template)
    {
        $this->pageTemplates[$template->getName()] = $template;

        return $this;
    }

    /**
     * @param string $screenshot
     */
    public function setScreenshot($screenshot)
    {
        $this->screenshot = $screenshot;
    }

    /**
     * @return string
     */
    public function getScreenshot()
    {
        return $this->screenshot;
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

}
