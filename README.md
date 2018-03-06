## Installation
@see https://igor.io/2013/02/07/composer-stability-flags.html

> Now you have to set require dependency like this during developing. 
```yaml
"require": {
  "sylius/grid": "1.2.x-dev",
  "sylius/grid-bundle": "1.2.x-dev",
  "linemob/core-php": "dev-master",
  "phpmob/changmin": "dev-master",
  "phakpoom/linebot-bundle": "dev-master"
}
```
## README.md
1. LineBotBundle (CORE)
[SEE](https://github.com/phakpoom/LineBotBundle/blob/master/src/LineMob/LineBotBundle/README.md)
2. UserBundle
[SEE](https://github.com/phakpoom/LineBotBundle/blob/master/src/LineMob/UserBundle/README.md)
3. KeywordBundle
[SEE](https://github.com/phakpoom/LineBotBundle/blob/master/src/LineMob/KeywordBundle/README.md)
4. AdminBundle
[SEE](https://github.com/phakpoom/LineBotBundle/blob/master/src/LineMob/AdminBundle/README.md)

## Testing
```
$ ./vendor/bin/phpunit
```
