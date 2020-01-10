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

class ManufacturerEdit extends FormBase {

    /**
     * {@inheritdoc}.
     */
    // Метод для котороый возвращает ид формы.
    public function getFormId() {
        return 'ManufacturerEdit_form';
    }

    /**
     * {@inheritdoc}.
     */
    // Вместо hook_form.
    public function buildForm(array $form, FormStateInterface $form_state, $manufacturerId = '')
    {

		$method  = 'Manufacturers';
		$result = SofaApiLogic::send($method, $result, 'GET', $manufacturerId);


        $form['#tree'] = TRUE;
        $form['#prefix'] = '<div id="all-fieldset-wrapper">';
        $form['#suffix'] = '</div>';
		
        $form['id'] = array(
            '#type' => 'hidden',
            '#default_value' => $manufacturerId,
        );
		
        $form['label'] = array(
            '#type' => 'label',
            '#title' => $manufacturerId,
        );
		

        $form['name'] = array(
            '#type' => 'textfield',
            '#title' => 'Название',
            '#required' => TRUE,
        );
		
        if (isset($result['name']))
            $form['name']['#default_value'] = $result['name'];

        $form['email'] = array(
            '#type' => 'textfield',
            '#title' => 'email',
        );
        if (isset($result['email']))
            $form['email']['#default_value'] = $result['email'];

        $form['address'] = array(
            '#type' => 'textfield',
            '#title' => 'Адресс',
        );
        if (isset($result['address']))
            $form['address']['#default_value'] = $result['address'];

        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => 'Обновить',
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
        $result = SofaApiLogic::send($method, $result, 'PUT', $setting['id']);
		
        if($result === []){
            drupal_set_message('Успешно');
            //drupal_set_message(['#type' => 'item', '#markup' => print_r($result, true),]);
        } else {
            drupal_set_message('Не обновлено', 'error');
        }
    }

}