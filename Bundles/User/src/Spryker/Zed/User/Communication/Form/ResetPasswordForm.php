<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\User\Communication\Form;

use Spryker\Zed\User\Business\UserFacade;
use Spryker\Zed\User\Communication\Form\Constraints\CurrentPassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResetPasswordForm extends AbstractType
{

    const FIELD_CURRENT_PASSWORD = 'current_password';
    const FIELD_PASSWORD = 'password';

    /**
     * @var \Spryker\Zed\User\Business\UserFacade
     */
    protected $userFacade;

    /**
     * ResetPasswordForm constructor.
     *
     * @param \Spryker\Zed\User\Business\UserFacade $userFacade
     */
    public function __construct(UserFacade $userFacade)
    {
        $this->userFacade = $userFacade;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'reset_password';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addCurrentPasswordField($builder)
            ->addPasswordField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return self
     */
    protected function addCurrentPasswordField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_CURRENT_PASSWORD, 'password', [
            'label' => 'Current password',
            'constraints' => [
                new NotBlank(),
                new CurrentPassword([
                    'facadeUser' => $this->userFacade,
                ]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return self
     */
    protected function addPasswordField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_PASSWORD, 'repeated', [
            'constraints' => [
                new NotBlank(),
            ],
            'invalid_message' => 'The password fields must match.',
            'first_options' => ['label' => 'Password'],
            'second_options' => ['label' => 'Repeat Password'],
            'required' => true,
            'type' => 'password',
        ]);

        return $this;
    }

}
