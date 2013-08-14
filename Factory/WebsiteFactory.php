<?php

namespace Presta\CMSCoreBundle\Factory;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class WebsiteFactory extends AbstractModelFactory implements ModelFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(array $configuration = array())
    {
        $website = new $this->modelClassName();
        $website->setPath($configuration['path']);
        $website->setName($configuration['name']);
        $website->setAvailableLocales($configuration['available_locales']);
        $website->setDefaultLocale($configuration['default_locale']);
        $website->setTheme($configuration['theme']);

        $this->getObjectManager()->persist($website);

        foreach ($configuration['available_locales'] as $locale) {
            $this->getObjectManager()->bindTranslation($website, $locale);
        }

        return $website;
    }
}
