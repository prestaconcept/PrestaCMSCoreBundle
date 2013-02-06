<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Tests\Unit\Model;

use Presta\CMSCoreBundle\Tests\Unit\BaseUnitTestCase;
use Presta\CMSCoreBundle\Model\BlockManager;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class BlockManagerTest extends BaseUnitTestCase
{

    public function testAddBlocks()
    {
        $blockManager = new BlockManager();

        $this->assertTrue($blockManager->getBlocks() instanceof ArrayCollection);
        $this->assertEquals(0, count($blockManager->getBlocks()));

        $blockManager->addBlock('presta_cms.block.simple');
        $blockManager->addBlock('presta_cms.block.page_children');

        $this->assertEquals(2, count($blockManager->getBlocks()));
        $this->assertEquals(
            array('presta_cms.block.simple', 'presta_cms.block.page_children'),
            $blockManager->getBlocks()->toArray()
        );
    }
}
