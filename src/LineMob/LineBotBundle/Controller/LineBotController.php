<?php

namespace LineMob\LineBotBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use LineMob\LineBotBundle\Model\UserCatchLogInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LineBotController extends Controller
{
    public function hookAction(Request $request, string $botName)
    {
        if ('GET' === $request->getMethod()) {
            return Response::create('It\'s Work!!!');
        }

        $data = $this->container->get(sprintf('linemob.%s.bot', $botName))->run($request->getContent(), $request->headers->get('X-Line-Signature'));

        if (true === $this->container->getParameter('kernel.debug')) {
            var_dump($data);
        }

        return Response::create('');
    }
}
