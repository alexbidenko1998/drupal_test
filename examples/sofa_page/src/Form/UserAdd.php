<?php
/**
 * Created by PhpStorm.
 * User: saint
 * Date: 05.03.2018
 * Time: 19:02
 */

namespace Drupal\sofa_page\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\sofa_page\Controller\SofaApiLogic;

class UserAdd extends FormBase {

    /**
     * {@inheritdoc}.
     */
    // Метод для котороый возвращает ид формы.
    public function getFormId() {
        return 'UserAdd_form';
    }

    /**
     * {@inheritdoc}.
     */
    // Вместо hook_form.
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        //$setting = $form_state->getValues();
        //(['#type' => 'item', '#markup' => print_r($setting, true),]);

        /*if (isset($setting['type'])){
            if ($setting['type'] != $setting['old_type']) {
                $form_state->set('chosen_type', $setting['type']);
            }
        }*/

        $form['login'] = array(
            '#type' => 'textfield',
            '#title' => 'Логин',
            '#required' => TRUE,
        );

        $form['password'] = array(
            '#type' => 'textfield',
            '#title' => 'Пароль',
            '#required' => TRUE,
        );

        $form['email'] = array(
            '#type' => 'textfield',
            '#title' => 'email',
            '#required' => TRUE,
        );
		

        /*$form['roles'] = array(
            '#type' => 'select',
            '#title' => 'Роль',
            '#options' => [
                '1' => '1',
                '2' => '2',
                '3' => '3',
            ],
            '#empty_option' => '- Выбор -',
            '#required' => TRUE,
        );*/

           

        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => 'Создать',
            '#attributes' => [
                'class' => ['col-xs-12', 'btn-info']
            ]
        );
        return $form;
    }
    /**
     * {@inheritdoc}
     */
    // Вместо hook_form_validate.
    public function validateForm(array &$form, FormStateInterface $form_state){
    }

    /**
     * {@inheritdoc}
     */
    // Вместо hook_form_submit.
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $setting = $form_state->getValues();
        $result = [
			'login' => $setting['login'],
			'password' => $setting['password'],
			'email' => $setting['email'],
		];
		
        $method  = 'Users';
        $result = SofaApiLogic::send($method, $result, 'POST');
		drupal_set_message(['#type' => 'item', '#markup' => print_r($result, true),]);
        if($result === []){
			$language = \Drupal::languageManager()->getCurrentLanguage()->getId();
			$user = \Drupal\user\Entity\User::create();

			// Mandatory.
			$user->setPassword($setting['password']);
			$user->enforceIsNew();
			$user->setEmail($setting['email']);
			$user->setUsername($setting['login']);

			// Optional.
			$user->set('init', $setting['email']);
			$user->set('langcode', $language);
			$user->set('preferred_langcode', $language);
			$user->set('preferred_admin_langcode', $language);
			//$user->addRole('rid');
			$user->activate();

			// Save user account.
			$result = $user->save();
			
            drupal_set_message('Успешно');
            //drupal_set_message(['#type' => 'item', '#markup' => print_r($result, true),]);
        } else {
            drupal_set_message('Не обновлено', 'error');
        }
    }

}