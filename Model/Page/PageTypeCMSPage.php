<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Model\Page;

use Presta\CMSCoreBundle\Form\Page\PageDescriptionType;
use Presta\CMSCoreBundle\Model\Page;
use Presta\CMSCoreBundle\Model\ThemeManager;
use Presta\CMSCoreBundle\Model\WebsiteManager;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Admin\Pool;
use Symfony\Component\Form\FormFactory;

/**
 * Base page type for CMS
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class PageTypeCMSPage implements PageTypeInterface
{
    const TAB_CONTENT     = 'content';
    const TAB_DESCRIPTION = 'description';
    const SERVICE_ID      = 'presta_cms.page_type.cms_page';

    /**
     * @var WebsiteManager
     */
    protected $websiteManager;

    /**
     * @var ThemeManager
     */
    protected $themeManager;

    /**
     * @var FormFactory
     */
    protected $formFactory;

    public function __construct(WebsiteManager $websiteManager, ThemeManager $themeManager, FormFactory $formFactory)
    {
        $this->websiteManager = $websiteManager;
        $this->themeManager   = $themeManager;
        $this->formFactory    = $formFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return self::SERVICE_ID;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultSettings()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getEditTabs(Page $page)
    {
        $tabs = array('content');
        if ($page->getParent() instanceof Page) {
            $tabs = array_merge($tabs, array('description'));
        }

        return $tabs;
    }

    /**
     * {@inheritdoc}
     */
    public function getEditTabData($tab, Page $page, Pool $pool)
    {
        switch ($tab) {
            case self::TAB_CONTENT:
                return $this->getEditTabContentData($page, $pool);
                break;
            case self::TAB_DESCRIPTION:
                return $this->getEditTabDescriptionData($page, $pool);
                break;
            default:
                break;
        }

        return array();
    }

    /**
     * Returns data for the content tab when editing pages of this type
     *
     * @param Page $page
     * @param Pool $pool
     *
     * @return array
     */
    protected function getEditTabContentData(Page $page, Pool $pool)
    {
        $website = $this->websiteManager->getCurrentWebsite();

        $template = $this->themeManager
            ->getTheme($website->getTheme(), $website)
            ->getPageTemplate($page->getTemplate());

        /* Build a list of page zones in the ordering of the page template */
        $pageZones = $page->getZones();
        $zones = array();
        foreach ($template->getZones() as $templateZone) {
            if ($page->hasZone($templateZone)) {
                $pageZone = $pageZones[$templateZone->getName()];

                /* Add template configuration details that are not persisted in the page */
                $pageZone->setCols($templateZone->getCols());
                $pageZone->setRows($templateZone->getRows());
            } else {
                $pageZone = $templateZone;
                $pageZone->setId($page->getId() . '/' . $templateZone->getName());
                $page->addZone($pageZone);
            }

            $zones[] = $pageZone;
        }

        return array(
            'page'     => $page,
            'zones'    => $zones,
            'locale'   => $page->getLocale(),
            'website'  => $website
        );
    }

    /**
     * Returns data for the description tab when editing pages of this type
     *
     * @param Page $page
     * @param Pool $pool
     *
     * @return array
     */
    protected function getEditTabDescriptionData(Page $page, Pool $pool)
    {
        /** @var AdminInterface $mediaAdmin */
        $mediaAdmin = $pool->getAdminByAdminCode('sonata.media.admin.media');
        $media      = $mediaAdmin->getModelManager()->find(
            $mediaAdmin->getClass(),
            $page->getDescriptionMediaId()
        );
        if (null !== $media) {
            $page->setDescriptionMedia($media);
        }

        return array(
            'page'       => $page,
            'form'       => $this->formFactory->create(new PageDescriptionType($pool), $page)->createView()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getEditTabTemplate($tab)
    {
        return 'PrestaCMSCoreBundle:Admin/Page/CMSPage:_' . $tab . '.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function getData(Page $page)
    {
        return array(
            'template' => $this->themeManager->getCurrentTheme()->getPageTemplate($page->getTemplate())->getPath()
        );
    }
}
