# AdminBundle

AdminBundle is a symfony bundle for admin section use [https://github.com/phpmob/changmin].

> Required!!! sylius-resource

## Enabling

And then enable bundle in `AppKernel.php`

```php
public function registerBundles()
{
    $bundles = [
        ...
        new \LineMob\LineBotBundle\LineMobLineBotBundle(), # required
        new \LineMob\AdminBundle\LineMobAdminBundle(),
        ...
    ];
}
```
```yaml
# config.yml
imports:
    # use all
    - { resource: "@LineMobAdminBundle/Resources/config/app/main.yml" }
    
    # use a part of bundle UserBundle
    - { resource: "@LineMobAdminBundle/Resources/config/grid/line_user.yml" }
    - { resource: "@LineMobAdminBundle/Resources/config/grid/line_audit_input.yml" }
    
    # use a part of bundle KeywordBundle
    - { resource: "@LineMobAdminBundle/Resources/config/grid/line_keyword.yml" }
```

```yaml
# routing.yml
# use all
linemob_admin:
    resource: "@LineMobAdminBundle/Resources/config/routing/main.yml"
    
# use a part of bundle UserBundle    
linemob_admin_line_user:
    resource: "@LineMobAdminBundle/Resources/config/routing/line_user.yml"
linemob_admin_line_audit_input:
    resource: "@LineMobAdminBundle/Resources/config/routing/line_audit_input.yml"

# use a part of bundle KeywordBundle
linemob_admin_line_keyword:
    resource: "@LineMobAdminBundle/Resources/config/routing/line_keyword.yml"
    
```
