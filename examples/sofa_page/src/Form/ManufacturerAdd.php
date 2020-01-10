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
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;

class ManufacturerAdd extends FormBase {

    /**
     * {@inheritdoc}.
     */
    // Метод для котороый возвращает ид формы.
    public function getFormId() {
        return 'ManufacturerAdd_form';
    }

    /**
     * {@inheritdoc}.
     */
    // Вместо hook_form.
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $form['id'] = array(
            '#type' => 'textfield',
            '#title' => 'id',
            '#required' => TRUE,
        );

        $form['name'] = array(
            '#type' => 'textfield',
            '#title' => 'Название',
            '#required' => TRUE,
        );
		

        $form['email'] = array(
            '#type' => 'textfield',
            '#title' => 'email',
        );

        $form['address'] = array(
            '#type' => 'textfield',
            '#title' => 'Адресс',
        );

        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => 'Добавить',
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
			'id' => $setting['id'],
			'name' => $setting['name'],
			'email' => $setting['email'],
			'address' => $setting['address']
		];
		
		
        $method  = 'Manufacturers';
        $result = SofaApiLogic::send($method, $result, 'POST');
		
        if($result === []){
            drupal_set_message('Успешно');
            //drupal_set_message(['#type' => 'item', '#markup' => print_r($result, true),]);
        } else {
            drupal_set_message('Не обновлено', 'error');
        }
    }

}