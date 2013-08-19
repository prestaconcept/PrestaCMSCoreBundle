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

use Presta\CMSCoreBundle\Doctrine\Phpcr\Block;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class BlockTest extends BaseUnitTestCase
{
    public function testBlock()
    {
        $block = new Block();

        $block->setType('presta_cms.block.simple');
        $this->assertEquals('presta_cms.block.simple', $block->getType());

        $this->assertEquals(false, $block->isAdminMode());
        $block->setAdminMode();
        $this->assertEquals(true, $block->isAdminMode());

        $this->assertEquals(true, $block->isEditable());
        $block->setIsEditable(false);
        $this->assertEquals(false, $block->isEditable());

        $this->assertEquals(true, $block->isDeletable());
        $block->setIsDeletable(false);
        $this->assertEquals(false, $block->isDeletable());

        $block->setLocale('en');
        $this->assertEquals('en', $block->getLocale());

        $this->assertEquals(array(), $block->getSettings());
        $block->setSetting('is_awesome', true);
        $this->assertEquals(array('is_awesome' => true), $block->getSettings());

        $this->assertEquals(true, $block->isActive());
        $block->setIsActive(false);
        $this->assertEquals(false, $block->isActive());

        $block->setId('/website/default/.._..__/block-id');
        $this->assertEquals('websitedefaultblock-id', $block->getHtmlId());
    }
}
