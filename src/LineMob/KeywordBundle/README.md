# KeywordBundle

KeywordBundle is a symfony bundle for line static response management.

> Required!!! sylius-resource

## Enabling

And then enable bundle in `AppKernel.php`

```php
public function registerBundles()
{
    $bundles = [
        ...
        new \LineMob\LineBotBundle\LineMobLineBotBundle(), # required
        new \LineMob\KeywordBundle\LineMobKeywordBundle(),
        ...
    ];
}
```
```yaml
# config.yml
- { resource: "@LineMobKeywordBundle/Resources/config/app/main.yml" }
```
