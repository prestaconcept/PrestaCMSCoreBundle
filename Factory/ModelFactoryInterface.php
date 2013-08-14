<?php

namespace Presta\CMSCoreBundle\Factory;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
interface ModelFactoryInterface
{
    /**
     * Create new object based on a configuration
     *
     * @param  array $configuration
     * @return mixed
     */
    public function create(array $configuration = array());
}
