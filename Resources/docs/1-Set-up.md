Installation
=============

## Get the bundle

    php composer.phar require presta/cms-core-bundle:dev-master --no-update
    php composer.phar update presta/cms-core-bundle

## Easy extend it in your application

    php app/console sonata:easy-extends:generate SonataAdminBundle --dest=src
    php app/console sonata:easy-extends:generate PrestaCMSCoreBundle --dest=src

## Update the Kernel

```php
// app/config/AppKernel.php
        $bundles = array(
            // ...
            // CMF bundles
            new Doctrine\Bundle\PHPCRBundle\DoctrinePHPCRBundle(),
            new Liip\DoctrineCacheBundle\LiipDoctrineCacheBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new Symfony\Cmf\Bundle\CoreBundle\SymfonyCmfCoreBundle(),
            new Symfony\Cmf\Bundle\RoutingExtraBundle\SymfonyCmfRoutingExtraBundle(),
            new Symfony\Cmf\Bundle\MenuBundle\SymfonyCmfMenuBundle(),
            new Symfony\Cmf\Bundle\ContentBundle\SymfonyCmfContentBundle(),
            new Symfony\Cmf\Bundle\BlockBundle\SymfonyCmfBlockBundle(),
            // Admin
            new Symfony\Cmf\Bundle\TreeBrowserBundle\SymfonyCmfTreeBrowserBundle(),
            new Sonata\jQueryBundle\SonatajQueryBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            new Sonata\DoctrinePHPCRAdminBundle\SonataDoctrinePHPCRAdminBundle(),
            new Sonata\SeoBundle\SonataSeoBundle(),
            new Sonata\EasyExtendsBundle\SonataEasyExtendsBundle(),
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
admin:
    resource: "@SonataAdminBundle/Resources/config/routing/sonata_admin.xml"
    prefix: /admin

_sonata_admin:
    resource: .
    type: sonata_admin
    prefix: /admin

PrestaCMSCoreBundle:
    resource: "@PrestaCMSCoreBundle/Resources/config/routing.yml"
    prefix:   /admin
    
phpcrodmbrowser:
    resource: "@SonataDoctrinePHPCRAdminBundle/Resources/config/routing/phpcrodmbrowser.xml"
    prefix: /admin/browser
```

## Configure the app

```yaml
imports:
    - { resource: parameters.yml }
    - { resource: security.yml }
    - { resource: '@PrestaCMSCoreBundle/Resources/config/config.yml' }
    - { resource: '@PrestaCMSCoreBundle/Resources/config/theme_default.yml' }

framework:
    #esi:             ~
    translator:      { fallback: "%locale%" }
    secret:          "%secret%"
    router:
        resource: "%kernel.root_dir%/config/routing.yml"
        strict_requirements: "%kernel.debug%"
    form:            true
    csrf_protection: true
    validation:      { enable_annotations: true }
    templating:      { engines: ['twig'] } #assets_version: SomeVersionScheme
    default_locale:  "%locale%"
    trust_proxy_headers: false # Deprecated in 2.0; will be removed in 2.3 (used trusted_proxies instead)
    trusted_proxies: ~
    session:         ~

# Twig Configuration
twig:
    debug:            "%kernel.debug%"
    strict_variables: "%kernel.debug%"

# Assetic Configuration
assetic:
    debug:          "%kernel.debug%"
    use_controller: false
    bundles:
        - ApplicationSonataAdminBundle
        - ApplicationPrestaCMSCoreBundle
    #java: /usr/bin/java
    filters:
        cssrewrite: ~
        #closure:
        #    jar: "%kernel.root_dir%/Resources/java/compiler.jar"
        #yui_css:
        #    jar: "%kernel.root_dir%/Resources/java/yuicompressor-2.4.7.jar"

# Doctrine Configuration
doctrine:
    dbal:
        driver:   "%database_driver%"
        host:     "%database_host%"
        port:     "%database_port%"
        dbname:   "%database_name%"
        user:     "%database_user%"
        password: "%database_password%"
        charset:  UTF8

    orm:
        auto_generate_proxy_classes: "%kernel.debug%"
        auto_mapping: true

# Swiftmailer Configuration
swiftmailer:
    transport: "%mailer_transport%"
    host:      "%mailer_host%"
    username:  "%mailer_user%"
    password:  "%mailer_password%"
    spool:     { type: memory }

# cmf configuration
doctrine_phpcr:
    # configure the PHPCR session
    session:
        backend: %phpcr_backend%
        workspace: %phpcr_workspace%
        username: %phpcr_user%
        password: %phpcr_pass%
    # enable the ODM layer
    odm:
        auto_mapping: true
        auto_generate_proxy_classes: %kernel.debug%
        locales:
            en:
                - en
                - de
                - fr
            de:
                - de
                - en
                - fr
            fr:
                - fr
                - en
                - de
sonata_block:
    default_contexts: [cms]
    blocks:
        sonata.block.service.text:

sonata_admin:
    title:      Presta CMS Sandbox
    templates:
        # default global templates
        layout:  ApplicationSonataAdminBundle::custom_layout.html.twig
    dashboard:
        blocks:
            - { position: hidden, type: presta_cms.block.dashboard.cms, settings: {display: 'sonata'}}
            -
                position: left
                type: sonata.block.service.text
                settings:
                    content: |
                        <h2>Welcome in Presta CMS Sandbox</h2>
                        <p>
                            This is an early release and will be enhanced progressively.
                        </p>
                        <p>
                            Right now features are :
                            <ul>
                                <li>Multiple websites by configuration</li>
                                <li>Multilangue</li>
                                <li>Custom theming</li>
                                <li>Page administration</li>
                            </ul>
                        </p>
                        <p>
                            You can use it freely for your project, open / correct tickets on Github via Pull request.
                        </p>
                        <p>
                            We hope it will help you on your project.
                        </p>
                        <p>
                            Presta CMS is based on Symfony CMF so we would like to thanks people working on it.
                        </p>
                        <p>
                            Presta CMS is sponsored by <a href="www.prestaconcept.net">PrestaConcept</a>
                        </p>

presta_cms_core: ~

symfony_cmf_menu:
    menu_basepath: /website/sandbox/menu
    multilang:
      locales: %locales%
```
