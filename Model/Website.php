<?php

namespace Presta\CMSCoreBundle\Model;

use Symfony\Cmf\Bundle\CoreBundle\Translatable\TranslatableInterface;

class Website implements TranslatableInterface
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var string $locale
     *
     */
    protected $locale;

    /**
     * @var string $theme
     */
    protected $theme;

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var array $availableLocales
     */
    protected $availableLocales;

    /**
     * Used by Admin edition
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }
}
