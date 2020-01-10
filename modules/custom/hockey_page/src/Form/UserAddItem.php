<?php
/**
 * Created by PhpStorm.
 * User: saint
 * Date: 05.03.2018
 * Time: 19:02
 */

namespace Drupal\hockey_page\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\hockey_page\Controller\HockeyApiLogic;

class UserAddItem extends FormBase {
    /**
     * {@inheritdoc}.
     */
    // Метод для котороый возвращает ид формы.
    public function getFormId() {
        return 'UserAddItem_form';
    }

    /**
     * {@inheritdoc}.
     */
    // Вместо hook_form.
    public function buildForm(array $form, FormStateInterface $form_state, $userId = '') {


        $method = 'getJsonDocument';
        $data = ['name' => 'shop'];
        $result = HockeyApiLogic::send($method, $data);

        $arr = [];
        $arr2 = [];

        $result = HockeyApiLogic::filter('category','med', 'NotEquals', $result);
        $result = HockeyApiLogic::filter('category','energy', 'NotEquals', $result);
        $result = HockeyApiLogic::filter('category','spirit', 'NotEquals', $result);
        $result = HockeyApiLogic::filter('category','kit', 'NotEquals', $result);
        $result = HockeyApiLogic::filter('category','token', 'NotEquals', $result);
        $result = HockeyApiLogic::filter('category','money', 'NotEquals', $result);
        $result = HockeyApiLogic::filter('category','player', 'NotEquals', $result);

        foreach ($result as $item) {
            $arr[$item['id']] = $item['description'];
        }

        $method = 'getJsonDocument';
        $data = ['name' => 'legendary'];
        $result = HockeyApiLogic::send($method, $data);


        foreach ($result as $item) {
            $arr2[$item['id']] = $item['name'].' '.$item['surname'];
        }

        $form['userId'] = array(
            '#type' => 'hidden',
            '#default_value' => $userId,
        );
        $form['arr'] = array(
            '#type' => 'hidden',
            '#default_value' => $arr,
        );
		
        $form['add'] = array(
            '#type' => 'select',
            '#title' => 'ID Товара',
            '#options' => $arr,
            '#empty_option' => '- Выбор -',
        );

        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => 'Разблокировать предмет',
            '#name' => 'submit',
            '#attributes' => [
                'class' => ['col-xs-12', 'btn-success']
            ]
        );
		
        $form['add2'] = array(
            '#type' => 'select',
            '#title' => 'ID легендарного',
            '#options' => $arr2,
            '#empty_option' => '- Выбор -',
        );

        $form['submit2'] = array(
            '#type' => 'submit',
            '#value' => 'Разблокировать легендарного',
            '#name' => 'submit2',
            '#attributes' => [
                'class' => ['col-xs-12', 'btn-success']
            ]
        );


        $form['#prefix'] = '<div class="row"><div class="col-xs-12">';
        $form['#suffix'] = '</div></div>';

        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        $trigger = $form_state->getTriggeringElement();
		
        if($trigger['#name'] == 'submit') {
			$method = 'accountAddItem';
			$data = [
				'accountId' =>  $form_state->getValue('userId'),
				'itemId' => $form_state->getValue('add'),
			];
        }
        else {
			$method = 'accountAddLegendary';
			$data = [
				'accountId' =>  $form_state->getValue('userId'),
				'itemId' => $form_state->getValue('add2'),
			];
		}
		
        $result = HockeyApiLogic::send($method, $data, 'object');

        if($result !== array()){
            drupal_set_message('Успешно');
             //drupal_set_message(['#type' => 'item', '#markup' => print_r($result, true),]);
        } else {
            drupal_set_message('Ошибка');
        }
    }

}