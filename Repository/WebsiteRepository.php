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
use Doctrine\ORM\EntityRepository;
use Gedmo\Translatable\TranslatableListener;

use Application\PrestaCMS\CoreBundle\Entity\Website;

/**
 * Website Repository
 * 
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien nbastien@prestaconcept.net
 */
class WebsiteRepository extends EntityRepository
{
    public function getDefaultWebsite($locale)
    {
        return $this->findOneBy(array('is_default' => true));
    }
    

    public function getAvailableWebsites()
    {
        return $this->findBy(array('is_active' => true));
    }


    /**
     * Return an active website for current host
     *
     * @param   string $host
     * @return  Application\PrestaCMS\CoreBundle\Entity\Website
     */
    public function findByHost($host)
    {
        $query = $this->createQueryBuilder('w')
            ->where('w.host = :host and w.is_active = :is_active')
            ->setParameters(array('host' => $host, 'is_active' => true))
            ->getQuery()
            ->setHint(
                \Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
                'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
            )
        ;

        return $query->getResult();
    }
}

