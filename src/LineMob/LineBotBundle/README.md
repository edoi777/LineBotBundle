# LineBotBundle
LineBotBundle is a symfony bundle for the [https://github.com/linemob/core-php]

## Enabling
And then enable bundle in `AppKernel.php`

```php
public function registerBundles()
{
    $bundles = [
        ...       
         new \LineMob\LineBotBundle\LineMobLineBotBundle(), # required
        ...
    ];
}
```

```yaml
# config.yml
- { resource: "@LineMobLineBotBundle/Resources/config/app/main.yml" }
```


## Configuration
```yaml
line_mob_line_bot:
    bots:
        NAME_OF_BOT:         
            line_channel_access_token: A LINE ACCESS TOKEN
            line_channel_secret: A LINE SECRET
            commands:
                - FIRST COMMAND CLASS
                - ...
                - LAST COMMAND CLASS
            middlewares:
                - tactician.middleware.locking            
                - MIDDLEWARE SERVICE               
                - tactician.middleware.command_handler     
                
        # Multiple bots        
        NAME_OF_BOT_2:
            line_channel_access_token: A LINE ACCESS TOKEN
            line_channel_secret: A LINE SECRET
            commands:
                - FIRST COMMAND CLASS
                - ...
                - LAST COMMAND CLASS
            middlewares:
                - tactician.middleware.locking            
                - MIDDLEWARE SERVICE               
                - tactician.middleware.command_handler                  
```

## Web Hook Controller

Add webhook routing from your `app/config/routing.yml`

```yaml
# app/config/routing.yml
LineMobLinebotBundle:
    resource: "@LineMobLineBotBundle/Resources/config/routing/main.yml"
```

Now, You can set `Webhook URL` on Line console to `https://{YOURDOMAIN.COM}/line-hook/{NAME_OF_BOT}`


## Configuration Example
```yaml
line_mob_line_bot:
    bots:
        example:
            log: true # enable log response
            logger: monolog.logger # logger service 
            #use_sender_mocky: true # for testing see below
            http_client_class: LineMob\Core\HttpClient\GuzzleHttpClient # must implement LINE\LINEBot\HTTPClient
            line_channel_access_token: 'Y89jwTWqx1yl5P09PONuamBUYHyZc64dVrN2H6jS'
            line_channel_secret: 'my_secret'
            commands:
                - LineMob\Core\Command\FallbackCommand              
            middlewares:
                - tactician.middleware.locking            
                - linemob.middleware.dummy_fallback        
                - tactician.middleware.command_handler                    
```

## Test line sender in local
In order to testing without connect to line server. Just run script

```$php bin/console linemob:bot:mock_run {YOUR_BOT_NAME} -t {YOUR_TEXT}```

> Remind!! : Bots config `use_sender_mocky` must be `true`.
