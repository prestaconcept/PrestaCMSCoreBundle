<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * (c) Nicolas Bastien nbastien@prestaconcept.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrestaCMS\CoreBundle\Model;

/**
 * Template Model
 *
 * @author Nicolas Bastien nbastien@prestaconcept.net
 */
class Template
{
    /**
     * @var string
     */
    protected $_path;

    /**
     * @var string
     */
    protected $_name;

    /**
     * @param string $name
     * @param string $path
     */
    public function __construct($name, $path)
    {
        $this->_name = $name;
        $this->_path = $path;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->_path;
    }
}