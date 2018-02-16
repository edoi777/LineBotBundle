# UserBundle

UserBundle is a symfony bundle for user management.

> Required!!! sylius-resource

## Enabling

And then enable bundle in `AppKernel.php`

```php
public function registerBundles()
{
    $bundles = [
        ...
        new \LineMob\LineBotBundle\LineMobLineBotBundle(), # required
        new \LineMob\UserBundle\LineMobUserBundle(),
        ...
    ];
}
```
```yaml
# config.yml
- { resource: "@LineMobUserBundle/Resources/config/app/main.yml" }
```

## Configuration

```yaml
line_mob_user:
    user_class: AppBundle\Entity\LineUser # your custom user class default `LineMob\UserBundle\Model\LineUser`
    manager: custom.manager.service # custom manager
    stick_line_user: false # One line user can only have one web user.
    allow_logout: true # allow logout
    active_cmd_ttl: '+15 minutes' # action command lifetime               
    audit_mode: ~ # can be 'by_user', 'all'             
```
