<?php

namespace SumoCoders\FrameworkMultiUserBundle\Form;

use SumoCoders\FrameworkMultiUserBundle\DataTransferObject\UserDataTransferObject;
use SumoCoders\FrameworkMultiUserBundle\Form\Interfaces\FormWithDataTransferObject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddUserType extends AbstractType implements FormWithDataTransferObject
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->getDataTransferObjectClass(),
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'userName',
            TextType::class
        )->add(
            'displayName',
            TextType::class
        )->add(
            'email',
            EmailType::class
        )->add(
            'plainPassword',
            RepeatedType::class,
            [
                'type' => PasswordType::class,
            ]
        );
    }

    public function getName()
    {
        return 'multi_user_form_add_user';
    }

    public function getDataTransferObjectClass()
    {
        return UserDataTransferObject::class;
    }
}
