<?php
/*
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Document\Website;

use Doctrine\ODM\PHPCR\DocumentRepository as BaseDocumentRepository;

use Presta\CMSCoreBundle\Document\Website;

/**
 * Website Repository
 *
 * @package    Presta
 * @subpackage CMSCoreBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class Repository extends BaseDocumentRepository
{

    public function getDefaultWebsite()
    {
        return $this->findOneBy(array('is_default' => true));
    }


    public function getAvailableWebsites()
    {
        return $this->findAll();

        //todo faire marcher le filtre is active sachant que c'est une propriété traduite !
        return $this->findBy(array('is_active' => true));
    }


    /**
     * Return an active website for current host
     *
     * @param   string $host
     * @return  Website
     */
    public function findByHost($host)
    {
        var_dump('todo : Website repository findByHost()');die;
    }

}