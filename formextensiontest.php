<?php

use PrestaShopBundle\Form\Admin\Type\SwitchType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;

class formextensiontest extends Module
{
    public function __construct()
    {
        $this->name = 'formextensiontest';
        $this->author = 'Invertus';
        $this->version = '1.0.0';
        $this->displayName = 'Form extension test';

        parent::__construct();
    }

    public function install()
    {
        return parent::install() &&
            $this->registerHook('actionlanguageFormBuilderModifier') &&
            $this->registerHook('displaylanguageFormRest');
    }

    public function hookactionlanguageFormBuilderModifier($params)
    {
        $this->buildCustomFormFields($params['form_builder']);
    }

    public function hookdisplaylanguageFormRest($params)
    {
        return $this->renderCustomFormFields($params['form']);
    }

    private function buildCustomFormFields(FormBuilderInterface $formBuilder)
    {
        $formBuilder
            ->add('test_input', TextType::class, [
                'required' => false,
                'data' => 'test value',
                'label' => 'Test input',
            ])
            ->add('test_switch', SwitchType::class, [
                'required' => false,
                'data' => true,
                'label' => 'Test switch',
            ])
        ;
    }

    private function renderCustomFormFields(FormView $formView)
    {
        /** @var Twig_Environment $twig */
        $twig = $this->get('twig');

        return $twig->render(
            $this->getLocalPath().'/views/templates/admin/form_fields.html.twig',
            [
                'custom_fields' => [
                    $formView->children['test_input'],
                    $formView->children['test_switch'],
                ],
            ]
        );
    }
}
