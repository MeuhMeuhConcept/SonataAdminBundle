# MMC SonataAdminBundle

Implatation of SonataAdmin for MMC

## Installation

Add the repository in composer.json
```json
"repositories" : [
    {
        "type" : "vcs",
        "url" : "git@git.meuhmeuhconcept.fr:mmc/SonataAdminBundle.git"
    }
],
```

Via composer
```bash
composer require mmc/sonata-admin-bundle
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
    title : ExampleOfTitle
    dashboard:
        blocks: []
        groups:
            sonata.admin.group.content:
                label:           Content
                icon:            '<i class="fa fa-th"></i>'
                items: ~ # Add class item here

    templates:
        layout: MMCSonataAdminBundle::sonata_layout.html.twig
    security:
        handler: sonata.admin.security.handler.role

twig:
    form_themes:
        - 'SonataCoreBundle:Form:datepicker.html.twig'
        - 'MMCSonataAdminBundle:Form:image_preview.html.twig'
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