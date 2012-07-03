<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien nbastien@prestaconcept.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrestaCMS\CoreBundle\Repository;

use Doctrine\ORM\Query;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use Gedmo\Translatable\TranslatableListener;

use Application\PrestaCMS\CoreBundle\Entity\Website;

/**
 * Page Repository
 * 
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien nbastien@prestaconcept.net
 */
class PageRepository extends NestedTreeRepository
{
    /**
     * Return root node for navigation
     * 
     * @param  Website $website
     * @param  string  $navigation
     * @return Page 
     */
    public function getNavigationRoot(Website $website, $navigation)
    {
        return $this->findOneBy(array(
            'isActive' => true, 
            'website' => $website,
            'type' => 'nav_root',
            'name' => $navigation
        ));
    }
    
    /**
     * Return children pages for a node formatted as array
     * 
     * Method used to build pages trees
     * 
     * @param  Website $website
     * @param  Page $node
     * @param  boolean $direct
     * @param  string $sortByField
     * @param  string $direction
     * @return Array 
     */
    public function getChildrenPages(Website $website, $node = null, $direct = false, $sortByField = null, $direction = 'ASC')
    {
        $query = $this->childrenQuery($node, $direct, $sortByField, $direction);
        $query->setHint(
            Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );
        $query->setHint(TranslatableListener::HINT_TRANSLATABLE_LOCALE, $website->getLocale());
        return $query->getArrayResult();
    }
    
    /**
     * Return single pages for a website formatted as array
     * 
     * @param  Website $website
     * @return array 
     */
    public function getSinglePages(Website $website)
    {
        $query = $this->createQueryBuilder('p')
            ->where('p.parent IS NULL and p.website = :website and p.type != :type')
            ->setParameters(array('website' => $website, 'type' => 'nav_root'))
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->setHint(
                \Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
                'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
            )->setHint(
                \Gedmo\Translatable\TranslatableListener::HINT_TRANSLATABLE_LOCALE,
                $website->getLocale()
            );
        return $query->getArrayResult();
    }
    
    /**
     * Get page by id
     * 
     * @param  Website $website
     * @param  integer $id
     * @return Page 
     */
    public function getPageById(Website $website, $id)
    {
        $page = $this->findOneBy(array(
            'website' => $website,
            'id' => $id
        ));
        if ($page != null) {
            $page->setLocale($website->getLocale());
            $this->_em->refresh($page);
        }
        return $page;
    }    
}

