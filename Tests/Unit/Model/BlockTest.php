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
    /**
     * @test Block::getHtmlId()
     */
    public function testHtmlId()
    {
        $block = new Block();

        $block->setId('/website/default/.._..__/block-id');
        $this->assertEquals('websitedefaultblock-id', $block->getHtmlId());
    }
}
