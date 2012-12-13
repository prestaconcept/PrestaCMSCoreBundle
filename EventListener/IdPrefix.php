<?php

namespace Presta\CMSCoreBundle\EventListener;

use Symfony\Cmf\Bundle\RoutingExtraBundle\Listener\IdPrefix as BaseIdPrefix;


/**
 */
class IdPrefix extends BaseIdPrefix
{
    //just need a setter to load if dynamiclly
    //see if we can make a pull request for this

    public function setPrefix($prefix)
    {
        $this->idPrefix = $prefix;
    }

}
