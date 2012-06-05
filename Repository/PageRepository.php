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

use Doctrine\ORM\EntityRepository;

use Application\PrestaCMS\CoreBundle\Entity\Website;

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
}

