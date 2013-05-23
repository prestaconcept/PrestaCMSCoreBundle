<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Model;

use Sonata\AdminBundle\Model\ModelManagerInterface;

/**
 * Menu Manager
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class MenuManager
{
    /**
     * @var ModelManagerInterface
     */
    protected $documentManager;

    public function __construct(ModelManagerInterface $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    /**
     * @return ModelManagerInterface
     */
    public function getDocumentManager()
    {
        return $this->documentManager;
    }
}
