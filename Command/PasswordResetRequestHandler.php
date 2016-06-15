<?php

namespace SumoCoders\FrameworkMultiUserBundle\Command;

use SumoCoders\FrameworkMultiUserBundle\Event\OnPasswordResetTokenCreated;
use SumoCoders\FrameworkMultiUserBundle\Event\PasswordResetTokenCreated;
use SumoCoders\FrameworkMultiUserBundle\User\PasswordReset as UserPasswordReset;
use SumoCoders\FrameworkMultiUserBundle\User\UserRepositoryCollection;
use Swift_Mailer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PasswordResetRequestHandler
{
    /**
     * @var UserRepositoryCollection
     */
    private $userRepositoryCollection;

    /**
     * @var Swift_Mailer
     */
    private $listener;

    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    /**
     * PasswordResetRequestHandler constructor.
     *
     * @param UserRepositoryCollection $userRepositoryCollection
     * @param EventDispatcherInterface $dispatcher
     * @param OnPasswordResetTokenCreated $listener
     */
    public function __construct(
        UserRepositoryCollection $userRepositoryCollection,
        EventDispatcherInterface $dispatcher,
        OnPasswordResetTokenCreated $listener
    ) {
        $this->userRepositoryCollection = $userRepositoryCollection;
        $this->dispatcher = $dispatcher;
        $this->listener = $listener;
    }

    /**
     * Creates a password reset token and sends an email to the user.
     *
     * @param RequestPasswordReset $command
     *
     * @return int
     */
    public function handle(RequestPasswordReset $command)
    {
        $user = $command->getUser();
        $user->generatePasswordResetToken();
        $repository = $this->userRepositoryCollection->findRepositoryByClassName(get_class($user));
        $repository->save($user);

        $this->sendPasswordResetToken($user);
    }

    /**
     * Sends the password reset token to the user.
     *
     * @param UserPasswordReset $user
     *
     * @return int
     */
    private function sendPasswordResetToken(UserPasswordReset $user)
    {
        $event = new PasswordResetTokenCreated($user);
        $this->dispatcher->addListener(PasswordResetTokenCreated::NAME, [$this->listener, 'onPasswordResetTokenCreated']);
        $this->dispatcher->dispatch('multi_user.event.password_reset_token_created', $event);
    }
}
