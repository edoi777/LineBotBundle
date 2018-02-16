<?php

namespace LineMob\LineBotBundle\Bot;

use LINE\LINEBot\Response;
use LineMob\Core\Receiver;
use LineMob\LineBotBundle\Context\LineBotContextInterface;
use Psr\Log\LoggerInterface;

class Bot
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Receiver
     */
    private $receiver;

    /**
     * @var LineBotContextInterface
     */
    private $lineBotContext;

    /**
     * @var LoggerInterface|null
     */
    private $logger;

    /**
     * @var bool
     */
    private $validateHeader = true;

    public function __construct($name, Receiver $receiver, LineBotContextInterface $lineBotContext, LoggerInterface $logger = null)
    {
        $this->name = $name;
        $this->receiver = $receiver;
        $this->lineBotContext = $lineBotContext;
        $this->logger = $logger;
    }

    /**
     * @return $this
     */
    public function disableValidationHeader()
    {
        $this->validateHeader = false;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $data
     * @param $signature
     * @return array
     */
    public function run($data, $signature)
    {
        if ($this->validateHeader && !$this->receiver->validate($data, $signature)) {
            throw new \RuntimeException("Invalid signature: " . $signature);
        }

        $this->lineBotContext->setRunningBot($this);

        $responses = $this->receiver->handle($data);

        $returnData = [];
        foreach ($responses as $response) {
            if ($response instanceof Response) {
                if ($this->logger && !empty($response->getJSONDecodedBody())) {
                    $this->logger->critical(json_encode($response->getJSONDecodedBody()));
                }
                $returnData[] = $response->getJSONDecodedBody();
            } else {
                if ($this->logger && !empty($response)) {
                    $this->logger->critical(json_encode(['response' => $response, 'trace' => json_encode(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5))]));
                }
                $returnData[] = $response;
            }
        }

        $this->lineBotContext->setRunningBot(null);

        return $returnData;
    }
}
