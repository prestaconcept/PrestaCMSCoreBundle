<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Model\Page;

use Presta\CMSCoreBundle\Model\ThemeManager;
use Presta\CMSCoreBundle\Model\WebsiteManager;
use Symfony\Component\DependencyInjection\Container;

/**
 * Base page type for CMS
 *
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class PageTypeCMSPage implements PageTypeInterface
{
    const TAB_CONTENT = 'content';
    const SERVICE_ID  = 'presta_cms.page_type.cms_page';

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var WebsiteManager
     */
    protected $websiteManager;

    /**
     * @var ThemeManager
     */
    protected $themeManager;

    public function __construct(Container $container, WebsiteManager $websiteManager, ThemeManager $themeManager)
    {
        $this->container      = $container;
        $this->websiteManager = $websiteManager;
        $this->themeManager   = $themeManager;
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
    public function getEditTabs()
    {
        return array (
            'cms_page' => 'content'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getEditTabData($tab, $page)
    {
        switch ($tab) {
            case self::TAB_CONTENT:
                $draft = $page;

                if (count($page->getZones()) == 0) {
                    // Todo améliorer ça !
                    // + prendre en compte le changement de template!
                    $repository = $this->container->get('doctrine_phpcr')->getManager()
                        ->getRepository('Presta\CMSCoreBundle\Doctrine\Phpcr\Page');

                    //If there is no corresponding data, initialisation with default configuration
                    $repository->initializeForTemplate($draft, $this->themeManager->getPageTemplateConfiguration($draft->getTemplate()));
                    $this->container->get('doctrine_phpcr')->getManager()->clear();
                    $draft = $this->container->get('doctrine_phpcr')->getManager()->findTranslation(
                        'Presta\CMSCoreBundle\Doctrine\Phpcr\Page',
                        $page->getId(),
                        $page->getLocale()
                    );
                }
                $website = $this->websiteManager->getCurrentWebsite();

                return array(
                    'page' 	   => $draft,
                    'locale'   => $page->getLocale(),
                    'website'  => $website,
                    'websiteId' => ($website) ? $website->getId() : null,
                    'template' => $this->themeManager->getPageTemplate($draft->getTemplate(), $draft)
                );
                break;
            default:
                break;
        }

        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function getEditTabTemplate($tab)
    {
        return 'PrestaCMSCoreBundle:Admin/Page/CMSPage:tab_content.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function getData($page)
    {
        return array(
            'template' => $this->themeManager->getPageTemplateFile($page->getTemplate())
        );
    }
}
