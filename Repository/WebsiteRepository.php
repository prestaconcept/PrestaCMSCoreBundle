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
     *
     * @author  Alain Flaus <aflaus@prestaconcept.net>
     *
     * @return  Application\PrestaCMS\CoreBundle\Entity\Website
     */
    public function findAvailableByHost($host)
    {
        return $this->findOneBy(
            array('is_active' => true),
            array('host'      => $host)
        );
    }
}

