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
    /**
     * @var PrestaCMS\CoreBundle\Model\ThemeManager 
     */
    protected $_themeManager;
    
    public function __construct($themeManager)
    {
        $this->_themeManager = $themeManager;
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
        //un seul tab : content
        //todo !
        $data = array();
        $template = 'default';
        return array(
            'template' => $this->_themeManager->getPageTemplate($template, $data)
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function getEditTabTemplate($tab)
    {
        return 'PrestaCMSCoreBundle:Admin/Page/CMSPage:tab_content.html.twig';
    }
}