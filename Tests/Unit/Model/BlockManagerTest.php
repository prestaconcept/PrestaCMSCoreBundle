<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Tests\Unit\Model;

use Presta\CMSCoreBundle\Tests\Unit\BaseUnitTestCase;
use Presta\CMSCoreBundle\Model\BlockManager;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class BlockManagerTest extends BaseUnitTestCase
{
    /**
     * @test BlockManager::addBlock()
     */
    public function testAddBlocks()
    {
        $blockManager = new BlockManager();

        $this->assertTrue(is_array($blockManager->getBlocks(BlockManager::TYPE_GLOBAL)));
        $this->assertEquals(0, count($blockManager->getBlocks(BlockManager::TYPE_GLOBAL)));

        $blockManager->addBlock('presta_cms.block.simple');
        $blockManager->addBlock('presta_cms.block.page_children');

        $this->assertEquals(2, count($blockManager->getBlocks(BlockManager::TYPE_GLOBAL)));
        $this->assertEquals(
            array('presta_cms.block.simple', 'presta_cms.block.page_children'),
            $blockManager->getBlocks(BlockManager::TYPE_GLOBAL)
        );
    }

    /**
     * @test BlockManager::testAddConfiguration()
     */
    public function testAddConfiguration()
    {
        $blockManager = new BlockManager();
        $configuration = array(
            'accepted' => array(
                'presta_cms.block.page_children',
            ),
        );
        $blockManager->addConfiguration($configuration);

        $expected = array(
            'global' => array(
                'excluded' => array(),
                'accepted' => array(
                    'presta_cms.block.page_children',
                ),
            ),
        );
        $this->assertEquals($expected, $blockManager->getConfigurations());

        $configuration = array(
            'accepted' => array(
                'presta_cms.block.page_children',
            ),
        );
        $blockManager->addConfiguration($configuration, 'foo-zone');

        $expected = array(
            'global' => array(
                'excluded' => array(),
                'accepted' => array(
                    'presta_cms.block.page_children',
                ),
            ),
            'foo-zone' => array(
                'excluded' => array(),
                'accepted' => array(
                    'presta_cms.block.page_children',
                ),
            ),
        );
        $this->assertEquals($expected, $blockManager->getConfigurations());
    }

    /**
     * @test BlockManager::getBlocks()
     */
    public function testGetBlocks()
    {
        // excluded block
        $blockManager = new BlockManager();
        $config = array(
            'excluded' => array(
                'presta_cms.block.simple',
            ),
        );
        $blockManager->addConfiguration($config);

        $blockManager->addBlock('presta_cms.block.simple');
        $blockManager->addBlock('presta_cms.block.page_children');

        $this->assertEquals(1, count($blockManager->getBlocks(BlockManager::TYPE_GLOBAL)));
        $expected = array(
            'presta_cms.block.page_children',
        );
        $this->assertEquals(
            $expected,
            array_values($blockManager->getBlocks(BlockManager::TYPE_GLOBAL))
        );

        // accepted block
        $config = array(
            'accepted' => array(
                'presta_cms.block.simple',
            ),
        );
        $blockManager->addConfiguration($config);

        $this->assertEquals(1, count($blockManager->getBlocks(BlockManager::TYPE_GLOBAL)));
        $expected = array(
            'presta_cms.block.simple',
        );
        $this->assertEquals(
            $expected,
            array_values($blockManager->getBlocks(BlockManager::TYPE_GLOBAL))
        );
    }
}
