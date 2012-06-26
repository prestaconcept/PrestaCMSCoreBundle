<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrestaCMS\CoreBundle\Model\Page;

/**
 * Base page type
 * 
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class PageTypeCMSPage implements PageTypeInterface
{
    const TAB_CONTENT = 'content';
    
    /**
     * @var Symfony\Component\DependencyInjection\Container
     */
    protected $container;
    
    /**
     * @var PrestaCMS\CoreBundle\Model\WebsiteManager 
     */
    protected $websiteManager;
    
    /**
     * @var PrestaCMS\CoreBundle\Model\ThemeManager 
     */
    protected $themeManager;
    
    public function __construct($container, $websiteManager, $themeManager)
    {
        $this->container = $container;
        $this->websiteManager = $websiteManager;
        $this->themeManager   = $themeManager;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName(){
        return 'cms_page';
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
                //Récupération de la révision draft!
                $repository = $this->container->get('doctrine')->getEntityManager()
                    ->getRepository('Application\PrestaCMS\CoreBundle\Entity\PageRevision');
                $draft = $repository->getDraftForPage($page);   

                return array(
                    'page' => $draft,
                    'website' => $this->websiteManager->getCurrentWebsite(),
                    'template' => $this->themeManager->getPageTemplate($draft->getTemplate(), $draft->getBlocksByZone())
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
}