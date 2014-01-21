<?php
/**
 * This file is part of the prestacms-sandbox project.
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Presta\CMSCoreBundle\Doctrine\Phpcr\Zone;
use Sonata\AdminBundle\Model\ModelManagerInterface;

/**
 * @author Mathieu Cottet <mcottet@prestaconcept.net>
 */
class ZoneManager
{
    /**
     * @var ModelManagerInterface
     */
    protected $modelManager;

    /**
     * @param ModelManagerInterface $modelManager
     */
    public function setModelManager($modelManager)
    {
        $this->modelManager = $modelManager;
    }

    /**
     * @return ModelManagerInterface
     */
    public function getModelManager()
    {
        return $this->modelManager;
    }

    /**
     * @param Zone  $zone
     * @param array $blockIds
     */
    public function updateZoneBlocks($zone, $blockIds)
    {
        $originBlocks = $zone->getBlocks();
        $sortedBlocks = new ArrayCollection();
        foreach ($blockIds as $blockId) {
            $name = $this->getBlockName($blockId);
            if ($originBlocks->get($name) !== null) {
                $sortedBlocks->add($originBlocks->get($name));
            }
        }
        $zone->setBlocks($sortedBlocks);

        $this->modelManager->getDocumentManager()->persist($zone);
        $this->modelManager->getDocumentManager()->flush();
    }

    /**
     * @param string $blockId
     *
     * @return string
     */
    protected function getBlockName($blockId)
    {
        $blockIdExploded = explode('/', $blockId);

        return $blockIdExploded[count($blockIdExploded) - 1];
    }
}
