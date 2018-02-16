<?php

namespace LineMob\UserBundle\Manager;

use LineMob\UserBundle\Model\LineUserInterface;
use PhpMob\CoreBundle\Model\WebUserInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;


final class LineUserSyliusWebUserManager implements LineUserRegistrationable, LineUserAuthenticationable
{
    /**
     * @var LineUserInterface
     */
    private $lineUser;

    /**
     * @var FactoryInterface
     */
    private $userFactory;

    /**
     * @var EntityRepository
     */
    private $userRepository;

    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * @var array
     */
    private $configs;

    public function __construct(
        FactoryInterface $userFactory,
        EntityRepository $userRepository,
        EncoderFactoryInterface $encoderFactory,
        array $configs
    )
    {
        $this->userFactory = $userFactory;
        $this->userRepository = $userRepository;
        $this->encoderFactory = $encoderFactory;
        $this->configs = $configs;
    }

    /**
     * {@inheritdoc}
     */
    public function setContext(LineUserInterface $lineUser): void
    {
        $this->lineUser = $lineUser;
    }

    /**
     * {@inheritdoc}
     */
    public function isExistsUser(string $username): bool
    {
        $username = strtolower($username);
        return (bool) $this->userRepository->findOneBy(['usernameCanonical' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function register(array $data): void
    {
        /** @var WebUserInterface $user */
        $user = $this->userFactory->createNew();
        $user->setUsername($data['username']);
        $user->setEmail($data['username']);
        $user->setPlainPassword($data['plain_password']);
        $user->setEnabled(true);
        $user->setDisplayName($data['name'] ?? null);

        $this->userRepository->add($user);

        // Auto login
        $this->setLoggedUser($user);

        if ($this->configs['stick_line_user']) {
            $this->lineUser->setWebUserLocked($user); // lock user
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setLoggedUser($user): void
    {
        $this->lineUser->setLoggedUser($user);
    }

    /**
     * {@inheritdoc}
     */
    public function getLoggedUser()
    {
        return $this->lineUser->getLoggedUser();
    }

    /**
     * {@inheritdoc}
     */
    public function login(string $username, string $password): bool
    {
        $username = strtolower($username);
        /** @var WebUserInterface $user */
        $user = $this->userRepository->findOneBy(['usernameCanonical' => $username, 'enabled' => true]);

        if (!$user) {
            return false;
        }

        if ($this->configs['stick_line_user']) {
            if ($this->lineUser->getWebUserLocked()->getId() !== $user->getId()) {
                return false;
            }
        }

        $encoder = $this->encoderFactory->getEncoder($user);

        if (!$encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt())) {
            return false;
        }

        $this->setLoggedUser($user);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function logout(): void
    {
        $this->lineUser->setLoggedUser(null);
    }
}
