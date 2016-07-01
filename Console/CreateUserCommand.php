<?php

namespace SumoCoders\FrameworkMultiUserBundle\Console;

use SumoCoders\FrameworkMultiUserBundle\Command\CreateUserHandler;
use SumoCoders\FrameworkMultiUserBundle\DataTransferObject\Form\BaseUser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CreateUserCommand extends Command
{
    /**
     * @var CreateUserHandler
     */
    private $handler;

    /**
     * CreateUserCommand constructor.
     *
     * @param CreateUserHandler $handler
     */
    public function __construct(CreateUserHandler $handler)
    {
        parent::__construct();
        $this->handler = $handler;
    }

    protected function configure()
    {
        $this
            ->setName('sumocoders:multiuser:create')
            ->setDescription('Create a user entity')
            ->addArgument(
                'username',
                InputArgument::REQUIRED,
                'The username of the user'
            )
            ->addArgument(
                'password',
                InputArgument::REQUIRED,
                'The password for the user'
            )
            ->addArgument(
                'displayName',
                InputArgument::REQUIRED,
                'The display name for the user'
            )
            ->addArgument(
                'email',
                InputArgument::REQUIRED,
                'The email address for the user'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $baseUserTransferObject = new BaseUser();
        $baseUserTransferObject->userName = $input->getArgument('username');
        $baseUserTransferObject->password = $input->getArgument('password');
        $baseUserTransferObject->displayName = $input->getArgument('displayName');
        $baseUserTransferObject->email = $input->getArgument('email');

        $this->handler->handle($baseUserTransferObject);

        $output->writeln($baseUserTransferObject->userName . ' has been created');
    }
}
