<?php

namespace SumoCoders\FrameworkMultiUserBundle\Command;

use SumoCoders\FrameworkMultiUserBundle\DataTransferObject\Interfaces\UserDataTransferObject;
use SumoCoders\FrameworkMultiUserBundle\User\UserRepositoryCollection;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

final class UpdateUserHandler extends AbstractUserHandler
{
    /** @var UserRepositoryCollection */
    private $userRepositoryCollection;

    /** @var EncoderFactoryInterface */
    private $encoderFactory;

    /**
     * @param EncoderFactoryInterface $encoderFactory
     * @param UserRepositoryCollection $userRepositoryCollection
     */
    public function __construct(
        EncoderFactoryInterface $encoderFactory,
        UserRepositoryCollection $userRepositoryCollection
    ) {
        $this->userRepositoryCollection = $userRepositoryCollection;
        $this->encoderFactory = $encoderFactory;
    }

    public function handle(UserDataTransferObject $userDataTransferObject)
    {
        $userEntity = $userDataTransferObject->getEntity();

        $userEntity->encodePassword($this->encoderFactory->getEncoder($userEntity));
        $repository = $this->getUserRepositoryForUser($this->userRepositoryCollection, $userEntity);
        $repository->save($userEntity);
    }
}
