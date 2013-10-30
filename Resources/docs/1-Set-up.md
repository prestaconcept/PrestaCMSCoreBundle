Installation
=============

## Get dependencies

Install and configure depencies of `PrestaCMSCoreBundle`:
  
* [SymfonyCMF](http://cmf.symfony.com/)
* [SonataDoctrinePhpcrAdminBundle](https://github.com/sonata-project/SonataDoctrinePhpcrAdminBundle)
* [SonataEasyExtendsBundle](https://github.com/sonata-project/SonataEasyExtendsBundle)
* [SonataSeoBundle](https://github.com/sonata-project/SonataSeoBundle)

## Get the bundle

    php composer.phar require presta/cms-core-bundle:dev-master

## Easy extend it in your application

    php app/console sonata:easy-extends:generate SonataAdminBundle --dest=src
    php app/console sonata:easy-extends:generate PrestaCMSCoreBundle --dest=src

## Update the Kernel

```php
// app/config/AppKernel.php
        $bundles = array(
            // ...
            // PrestaCMS
            new Presta\CMSCoreBundle\PrestaCMSCoreBundle(),
            // Extended bundles
            new Application\Sonata\AdminBundle\ApplicationSonataAdminBundle(),
            new Application\Presta\CMSCoreBundle\ApplicationPrestaCMSCoreBundle(),
        );

```

## Add routing

```yaml
# app/config/routing.yml
# ...
PrestaCMSCoreBundle:
    resource: "@PrestaCMSCoreBundle/Resources/config/routing.yml"
    prefix:   /admin
```

## Configure the app

```yaml
# app/config/config.yml
imports:
# ...
    - { resource: '@PrestaCMSCoreBundle/Resources/config/config.yml' }
    - { resource: '@PrestaCMSCoreBundle/Resources/config/theme_default.yml' }

# Assetic Configuration
assetic:
# ...
    bundles:
        - ApplicationSonataAdminBundle
        - ApplicationPrestaCMSCoreBundle

presta_cms_core: ~
```
