<?php
/*
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Document\Block;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;

use Symfony\Cmf\Bundle\BlockBundle\Document\BaseBlock as CmfBaseBlock;

/**
 * BaseBlock Model
 *
 * @package    Presta
 * @subpackage CMSCoreBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * @PHPCRODM\Document(referenceable=true, translator="attribute")
 */

class SimpleBlock extends BaseBlock
{
    /** @PHPCRODM\String(translated=true) */
    protected $title;

    /** @PHPCRODM\String(translated=true) */
    protected $content;

    /**
     * Returns the type
     *
     * @return string $type
     */
    function getType()
    {
        return 'presta_cms.block.simple';
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

}