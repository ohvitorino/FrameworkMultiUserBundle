<?php

namespace SumoCoders\FrameworkMultiUserBundle\Tests\Command;

use SumoCoders\FrameworkMultiUserBundle\Command\CreateUserHandler;
use SumoCoders\FrameworkMultiUserBundle\DataTransferObject\UserWithPasswordDataTransferObject;
use SumoCoders\FrameworkMultiUserBundle\User\InMemoryUserRepository;
use SumoCoders\FrameworkMultiUserBundle\User\UserRepositoryCollection;
use SumoCoders\FrameworkMultiUserBundle\User\UserWithPassword;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\PlaintextPasswordEncoder;

class CreateUserHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserRepositoryCollection
     */
    private $userRepositoryCollection;

    public function setUp()
    {
        $this->userRepository = new InMemoryUserRepository();
        $this->userRepositoryCollection = new UserRepositoryCollection([$this->userRepository]);
    }

    /**
     * Test if CreateUserHandler gets handled.
     */
    public function testCreateUserGetsHandled()
    {
        $handler = new CreateUserHandler(
            new EncoderFactory([UserWithPassword::class => new PlaintextPasswordEncoder()]),
            $this->userRepositoryCollection
        );

        $user = new UserWithPassword('sumo', 'randomPassword', 'sumocoders', 'sumo@example.dev');

        $userDataTransferObject = UserWithPasswordDataTransferObject::fromUser($user);
        $userDataTransferObject->plainPassword = 'randomPassword';

        $handler->handle($userDataTransferObject);

        $this->assertEquals(
            'sumo',
            $this->userRepository->findByUsername('sumo')->getUsername()
        );
        $this->assertEquals(
            'sumocoders',
            $this->userRepository->findByUsername('sumo')->getDisplayName()
        );
        $this->assertEquals(
            'randomPassword{' . $this->userRepository->findByUsername('sumo')->getSalt() . '}',
            $this->userRepository->findByUsername('sumo')->getPassword()
        );
        $this->assertEquals(
            'sumo@example.dev',
            $this->userRepository->findByUsername('sumo')->getEmail()
        );
    }
}
