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

use Presta\CMSCoreBundle\Document\Zone;

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
     * Set zones
     * 
     * @param  array $zones
     * @return \Presta\CMSCoreBundle\Document\Zone
     */
    public function setZones(array $zones)
    {
        $this->zones = $zones;

        return $this;
    }
    
    /**
     * Add a zone
     * 
     * @param  Zone $zone
     * @return \Presta\CMSCoreBundle\Document\Zone
     */
    public function addZone(Zone $zone)
    {
        $this->zones[$zone->getName()] = $zone;

        return $this;
    }
}