Website Configuration
==========================


# Overview

PrestaCMS is made to handle multiple :
 - websites
 - languages
 - environments (dev, preprod, production)


# Configuration

    presta_cms_core:
        websites:
            prestacms-sandbox:
                path: /website/sandbox
                hosts:
                    dev:
                        en:
                            locale: en
                            host: www.prestacms-sandbox.en.local
                        fr:
                            locale: fr
                            host: www.prestacms-sandbox.fr.local
                    prod:
                        en:
                            locale: en
                            host: www.prestacms-sandbox.com
                        fr:
                            locale: fr
                            host:  www.prestacms-sandbox.fr
            prestaconcept:
                path: /website/prestaconcept
                hosts:
                    dev:
                        fr:
                            locale: fr
                            host: www.prestaconcept.local
                    prod:
                        fr:
                            locale: fr
                            host: www.prestaconcept.net


# Environement

This environment step allow you to retrieve the correct url for a given website and locale in your "change language" links

In practice, PrestaCMS will load the current website based on host configuration. As each host is linked to an environement, WebsiteManager will
store it. Now you can invoke WebsiteManager get the website url in an other locale and it will automatically serve it for the same environment.

## Twig

    {{ websiteManager.getBaseUrlForLocale('fr') }}

## PHP

    $websiteManager->getBaseUrlForLocale('fr');


!This part is still a WIP