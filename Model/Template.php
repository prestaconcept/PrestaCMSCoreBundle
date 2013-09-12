<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Model;

use Presta\CMSCoreBundle\Model\Zone;

/**
 * Template Model
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class Template
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $zones;

    /**
     * @param string $name
     * @param string $path
     */
    public function __construct($name, $path)
    {
        $this->name = $name;
        $this->path = $path;
        $this->zones = array();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Returns zones
     *
     * @return array
     */
    public function getZones()
    {
        return $this->zones;
    }

    /**
     * @param array $zones
     */
    public function setZones(array $zones)
    {
        $this->zones = $zones;
    }

    /**
     * Add a zone
     *
     * @param  Zone $zone
     * @return Zone
     */
    public function addZone(Zone $zone)
    {
        $this->zones[$zone->getName()] = $zone;

        return $this;
    }
}
