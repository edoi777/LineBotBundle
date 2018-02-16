<?php

declare(strict_types=1);

namespace Linemob\LinebotBundle\Tests\DependencyInjection;

use LineMob\LineBotBundle\DependencyInjection\Configuration;
use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;


final class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    use ConfigurationTestCaseTrait;

    /**
     * @test
     */
    public function it_requires_line_channel_access_token_and_secret(): void
    {
        $this->assertConfigurationIsInvalid(
            [[
                'bots' => [
                    'my_bot' => null,
                ],
            ]]
        );

        $this->assertConfigurationIsValid(
            [[
                'bots' => [
                    'my_bot' => [
                        'line_channel_access_token' => 'foo',
                        'line_channel_secret' => 'foo'
                    ],
                ],
            ]]
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfiguration(): Configuration
    {
        return new Configuration();
    }
}
