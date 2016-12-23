# MeuhMeuhConcept SonataAdminBundle
[![Build Status](https://travis-ci.org/MeuhMeuhConcept/SonataAdminBundle.svg?branch=master)](https://travis-ci.org/MeuhMeuhConcept/SonataAdminBundle)

Implatation of SonataAdmin for MeuhMeuhConcept

## Installation

Via composer
```bash
composer require meuhmmeuhconcept/sonata-admin-bundle
```

Installs bundles web assets under a public web directory
```bash
bin/console assets:install
```
## Configuration

### Add bundles
In app/AppKernel.php, add following lines
```php
public function registerBundles()
{
    $bundles = [

        // ...

        new MMC\SonataAdminBundle\MMCSonataAdminBundle(),

        // These are the other bundles the SonataAdminBundle relies on
        new Sonata\CoreBundle\SonataCoreBundle(),
        new Sonata\BlockBundle\SonataBlockBundle(),
        new Knp\Bundle\MenuBundle\KnpMenuBundle(),

        // And finally, the storage and SonataAdminBundle
        new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
        new Sonata\AdminBundle\SonataAdminBundle(),

        // ...
    ];

    // ...
}
```

### Configure bundles

Add SonataAdmin configuration (dashboard, templates, security,...) and form themes in Twig configuration :

```yaml
# app/config/config.yml

sonata_block:
    default_contexts: [cms]
    blocks:
        # enable the SonataAdminBundle block
        sonata.admin.block.admin_list:
            contexts: [admin]

sonata_admin:
    templates:
        layout: MMCSonataAdminBundle::sonata_layout.html.twig
    security:
        handler: sonata.admin.security.handler.role

twig:
    form_themes:
        - 'SonataCoreBundle:Form:datepicker.html.twig'
```
Add roles hierarchy :
```yaml
# app/config/security.yml
    role_hierarchy:

        ROLE_SONATA_ADMIN:
            - ROLE_MMC_SONATA_ADMIN_ACTUALITY_ALL
            - ROLE_MMC_SONATA_ADMIN_ACTIVITY_ALL
            - ROLE_MMC_SONATA_ADMIN_EXPONENT_ALL
            - ROLE_MMC_SONATA_ADMIN_GUEST_ALL

        ROLE_ADMIN:             [ROLE_SONATA_ADMIN]
        ROLE_SUPER_ADMIN:       [ROLE_ADMIN, ROLE_ALLOWED_TO_SWITCH]

```
Add Sonata Admin route :
```yaml
# app/config/routing.yml
mmc_sonata_admin:
    resource: "@MMCSonataAdminBundle/Resources/config/routing.yml"
    prefix:   /admin
```
### Image preview

Install liip/imagine-bundle

[Official documentation](http://symfony.com/doc/current/bundles/LiipImagineBundle/installation.html)

Configure form theme :
```yaml
# app/config/config.yml

twig:
    form_themes:
        - 'MMCSonataAdminBundle:Form:image_preview.html.twig'
```

Add filters
```yaml
# app/config/config.yml

liip_imagine :

    resolvers :
        default :
            web_path : ~

    filter_sets :
        cache : ~
        admin_thumb:
            quality: 75
            filters:
                thumbnail: { size : [90, 50], mode : outbound }

        admin_poster:
            quality: 85
            filters:
                thumbnail: { size : [400, 300], mode : inset }
```

Note : If php_exif extension is not enable in your PHP container, add the following line :
```yaml
# app/config/parameters.yml && app/config/parameters.yml.dist

liip_imagine.meta_data.reader.class: Imagine\Image\Metadata\DefaultMetadataReader
```

## Customize

### Sonata Admin

Example :
```yaml
# app/config/config.yml

sonata_admin:
    title : ExampleOfTitle
    dashboard:
        blocks: []
        groups:
            sonata.admin.group.myCustomGroup:
                label:           myCustomLabel
                icon:            '<i class="fa fa-th"></i>'
                items: ~ # Add class item here
```