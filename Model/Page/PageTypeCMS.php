<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien nbastien@prestaconcept.net
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
 * @author     Nicolas Bastien nbastien@prestaconcept.net
 */
class PageTypeCMS implements PageTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getName(){
        return 'cms';
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
            'content' => ''
        );
    }
}