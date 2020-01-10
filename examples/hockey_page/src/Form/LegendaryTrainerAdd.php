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

class LegendaryTrainerAdd extends FormBase {

    /**
     * {@inheritdoc}.
     */
    // Метод для котороый возвращает ид формы.
    public function getFormId() {
        return 'LegendaryTrainerAdd_form';
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

        $form['id'] = array(
            '#type' => 'textfield',
            '#title' => 'id (покупки заведеной в Google и Apple)',
            '#required' => TRUE,
        );

        $form['name'] = array(
            '#type' => 'textfield',
            '#title' => 'Имя',
            '#required' => TRUE,
        );

        $form['surname'] = array(
            '#type' => 'textfield',
            '#title' => 'Фамилия',
            '#required' => TRUE,
        );
		
        /*$form['number'] = array(
            '#type' => 'textfield',
            '#title' => 'Фамилия',
            '#required' => TRUE,
        );*/

        $form['trainerType'] = array(
            '#type' => 'select',
            '#title' => 'Тип',
            '#options' => [
                'attack' => 'attack',
                'defend' => 'defend',
                'common' => 'common',
            ],
            '#empty_option' => '- Выбор -',
            '#required' => TRUE,
        );

        $form['level'] = array(
            '#type' => 'textfield',
            '#title' => 'Уровень',
            '#attributes' => array(
                ' type' => 'number',
            ),
            '#required' => TRUE,
        );

        $form['image'] = array(
            '#type' => 'textfield',
            '#title' => 'Изображение',
            '#required' => TRUE,
        );

        $form['attack'] = array(
            '#type' => 'textfield',
            '#title' => 'Бонус - Атака',
            '#attributes' => array(
                ' type' => 'number',
            ),
            '#required' => TRUE,
        );

        $form['defend'] = array(
            '#type' => 'textfield',
            '#title' => 'Бонус - Защита',
            '#attributes' => array(
                ' type' => 'number',
            ),
            '#required' => TRUE,
        );

        $form['attackOnEnemySide'] = array(
            '#type' => 'textfield',
            '#title' => 'Бонус - Атака на поле противника',
            '#attributes' => array(
                ' type' => 'number',
            ),
            '#required' => TRUE,
        );

        $form['defendOnAllySide'] = array(
            '#type' => 'textfield',
            '#title' => 'Бонус - Защита на своем поле',
            '#attributes' => array(
                ' type' => 'number',
            ),
            '#required' => TRUE,
        );

        $form['more'] = array(
            '#type' => 'textfield',
            '#title' => 'Бонус - Большинство',
            '#attributes' => array(
                ' type' => 'number',
            ),
            '#required' => TRUE,
        );

        $form['less'] = array(
            '#type' => 'textfield',
            '#title' => 'Бонус - Меньшиство',
            '#attributes' => array(
                ' type' => 'number',
            ),
            '#required' => TRUE,
        );

        $form['textNameRus'] = array(
            '#type' => 'textfield',
            '#title' => 'Имя в описании (Русский)',
            '#required' => TRUE,
        );

        $form['textNameEng'] = array(
            '#type' => 'textfield',
            '#title' => 'Имя в описании (Английский)',
            '#required' => TRUE,
        );

        $form['textNameGer'] = array(
            '#type' => 'textfield',
            '#title' => 'Имя в описании (Немецкий)',
            '#required' => TRUE,
        );

        $form['textRus'] = array(
            '#type' => 'textarea',
            '#title' => 'Описание (Русский)',
            '#required' => TRUE,
        );

        $form['textEng'] = array(
            '#type' => 'textarea',
            '#title' => 'Описание (Английский)',
            '#required' => TRUE,
        );

        $form['textGer'] = array(
            '#type' => 'textarea',
            '#title' => 'Описание (Немецкий)',
            '#required' => TRUE,
        );

        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => '  Добавить',
            '#attributes' => [
                'class' => ['col-xs-12', 'btn-info', 'glyphicon glyphicon-wrench']
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
        $trainer = [
			'id' => $setting['id'],
			'category' => 'player',
			'description' => "Тренер",
			'product' => [[[
				'type' => 'unlock',
				'item' => 'trainer',
				'trainerType' => $setting['trainerType'],
				'level' => $setting['level'],
				'name' => $setting['name'],
				'surname' => $setting['surname'],
				'image' => 0,
				'trainerBonus' => [
					'attack' => $setting['attack'],
					'defend' => $setting['defend'],
					'attackOnEnemySide' => $setting['attackOnEnemySide'],
					'defendOnAllySide' => $setting['defendOnAllySide'],
					'more' => $setting['more'],
					'less' => $setting['less']
				],
				'text' => [
					'RUSSIAN' => $setting['textRus'],
					'ENGLISH' => $setting['textEng'],
					'GERMAN' => $setting['textGer'],
				],
				'textName' => [
					'RUSSIAN' => $setting['textNameRus'],
					'ENGLISH' => $setting['textNameEng'],
					'GERMAN' => $setting['textNameGer'],
				],
				]]],
			'value' => [[
				'type' => 'in_app',
				'productId' => $setting['id']
				]],
		];
        $method = 'getJsonDocument';
		$data    = ['name' => 'shop'];
        $result = HockeyApiLogic::send($method, $data);
		$result[] = $trainer;
        $method  = 'setJsonDocument';
        $data    = ['name' => 'shop', 'json' => $result];
        $result = HockeyApiLogic::send($method, $data);
        if($result === array()){
            drupal_set_message('Успешно');
            //drupal_set_message(['#type' => 'item', '#markup' => print_r($result, true),]);
        } else {
            drupal_set_message('Не обновлено', 'error');
        }
    }

}