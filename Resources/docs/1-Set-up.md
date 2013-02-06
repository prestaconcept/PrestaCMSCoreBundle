Installation
=============


## Get the bundle

    php composer.phar require presta/cms-core-bundle:dev-master --no-update
    php composer.phar update presta/cms-core-bundle

## Easy extend it in your application

    php app/console sonata:easy-extends:generate SonataAdminBundle --dest=src
    php app/console sonata:easy-extends:generate PrestaCMSCoreBundle --dest=src

## Add routing

