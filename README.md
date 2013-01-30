# Presta CMS Core Bundle

This bundle contains the main logic of Presta CMS.

This bundle heavily takes advantage of Symfony CMF bundle.

## Features:

* Multiple website
* Custom theming
* Custom pages

## Installation

### Get the bundle

    php composer.phar require presta/cms-core-bundle:dev-master --no-update
    php composer.phar update presta/cms-core-bundle

### Easy extend it in your application

    php app/console sonata:easy-extends:generate SonataAdminBundle --dest=src
    php app/console sonata:easy-extends:generate PrestaCMSCoreBundle --dest=src

### Add routing


