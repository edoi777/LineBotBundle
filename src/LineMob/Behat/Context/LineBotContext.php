<?php

namespace LineMob\Behat\Context;

use Behat\Behat\Context\Context;
use LineMob\Behat\Service\SharedStorageInterface;
use LineMob\LineBotBundle\Bot\Bot;
use LineMob\LineBotBundle\Command\DevServerCommand;
use LineMob\UserBundle\Model\LineUserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Webmozart\Assert\Assert;

final class LineBotContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    /**
     * @var Bot
     */
    private $bot = null;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        ContainerInterface $container
    )
    {
        $this->sharedStorage = $sharedStorage;
        $this->container = $container;
    }

    /**
     * @Given ทดสอบบอท :name
     */
    public function setBot($name)
    {
        $this->bot = $this->container->get(sprintf('linemob.%s.bot', $name));
    }

    /**
     * @When :lineUser พิมพ์ข้อความว่า :text
     */
    public function createLineInputWithUser(LineUserInterface $lineUser, $text)
    {
        $mockContent = DevServerCommand::getLineHookMockUp($text, $lineUser);
        $data = $this->bot->disableValidationHeader()->run(json_encode($mockContent), 'mock');

        $this->saveResponse($data);
    }

    /**
     * @When พิมพ์ข้อความว่า :text
     */
    public function createLineInput($text)
    {
        $mockContent = DevServerCommand::getLineHookMockUp($text);
        $data = $this->bot->disableValidationHeader()->run(json_encode($mockContent), 'mock');

        $this->saveResponse($data);
    }

    /**
     * @Then /^(ข้อความจากไลน์บอท) จะต้องตอบกลับเป็นรูป$/
     */
    public function shouldGetImage($response)
    {
        Assert::eq('image', $response[0]['$messageBuilder'][0]['type']);
    }

    /**
     * @Then /^(ข้อความจากไลน์บอท) จะต้องตอบกลับเป็น ImageMap และมี action ทั้งหมด (\d+)$/
     */
    public function shouldGetImageMap($response, $actionsCount)
    {
        Assert::eq('imagemap', $response[0]['$messageBuilder'][0]['type']);
        Assert::count($response[0]['$messageBuilder'][0]['actions'], $actionsCount);
    }

    /**
     * @Then /^(ข้อความจากไลน์บอท) จะต้องตอบกลับเป็น Carousel$/
     */
    public function shouldGetCarousel($response)
    {
        Assert::eq('template', $response[0]['$messageBuilder'][0]['type']);
        Assert::eq('carousel', $response[0]['$messageBuilder'][0]['template']['type']);
    }

    /**
     * @Then /^(ข้อความจากไลน์บอท) จะต้องตอบกลับเป็น Button$/
     */
    public function shouldGetButton($response)
    {
        Assert::eq('template', $response[0]['$messageBuilder'][0]['type']);
        Assert::eq('buttons', $response[0]['$messageBuilder'][0]['template']['type']);
    }

    /**
     * @Then /^(ข้อความจากไลน์บอท) ที่เป็น Button ช่องที่ (\d+) จะปรากฏคำว่า "(.+)" และเมื่อกดจะได้คำว่า "(.+)"$/
     */
    public function shouldHasButtonWithText($response, $buttonIndex, $label, $message)
    {
        $buttonIndex = $buttonIndex - 1;

        Assert::eq('message', $response[0]['$messageBuilder'][0]['template']['actions'][$buttonIndex]['type']);
        Assert::eq($label, $response[0]['$messageBuilder'][0]['template']['actions'][$buttonIndex]['label']);
        Assert::eq($message, $response[0]['$messageBuilder'][0]['template']['actions'][$buttonIndex]['text']);
    }

    /**
     * @Then /^(ข้อความจากไลน์บอท) จะต้องตอบกลับเป็น Multiple$/
     */
    public function shouldGetMultiple($response)
    {
        Assert::greaterThan($response[0]['$messageBuilder'], 1);
    }

    /**
     * @Then /^(ข้อความจากไลน์บอท) จะต้องตอบกลับคำว่า (.+)$/
     */
    public function shouldGetText($response, $responseText)
    {
        Assert::eq($responseText, $response[0]['$messageBuilder'][0]['text']);
    }

    /**
     * @Then /^(ข้อความจากไลน์บอท) จะต้องตอบกลับประกอบด้วยคำว่า (.+)$/
     */
    public function shouldGetContainsText($response, $responseText)
    {
        Assert::contains($response[0]['$messageBuilder'][0]['text'], $responseText);
    }

    /**
     * @param $response
     */
    private function saveResponse($response)
    {
        $this->sharedStorage->set('lineResponse', $response);
    }
}
