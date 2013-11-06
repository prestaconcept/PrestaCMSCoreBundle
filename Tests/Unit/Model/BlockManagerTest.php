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
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

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

        $this->assertTrue(is_array($blockManager->getBlocks()));
        $this->assertEquals(0, count($blockManager->getBlocks()));

        $blockManager->addBlock('presta_cms.block.simple');
        $blockManager->addBlock('presta_cms.block.page_children');

        $this->assertEquals(2, count($blockManager->getBlocks()));
        $this->assertEquals(
            array('presta_cms.block.simple', 'presta_cms.block.page_children'),
            $blockManager->getBlocks()
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
            'excluded' => array(
                'presta_cms.block.faked',
            ),
        );
        try {
            $blockManager->addConfiguration($configuration);
            $this->fail();
        } catch (InvalidConfigurationException $e) {
            $this->assertEquals(
                'Cannot have accepted AND excluded blocks lists.',
                $e->getMessage()
            );
        }
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

        $this->assertEquals(1, count($blockManager->getBlocks()));
        $expected = array(
            'presta_cms.block.page_children',
        );
        $this->assertEquals(
            $expected,
            array_values($blockManager->getBlocks())
        );

        // accepted block
        $config = array(
            'accepted' => array(
                'presta_cms.block.simple',
            ),
        );
        $blockManager->addConfiguration($config);

        $this->assertEquals(1, count($blockManager->getBlocks()));
        $expected = array(
            'presta_cms.block.simple',
        );
        $this->assertEquals(
            $expected,
            array_values($blockManager->getBlocks())
        );
    }
}
