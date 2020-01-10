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

class LegendaryPlayerTAdd extends FormBase {

    /**
     * {@inheritdoc}.
     */
    // Метод для котороый возвращает ид формы.
    public function getFormId() {
        return 'LegendaryPlayerTAdd_form';
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
            '#title' => 'ID',
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

        $form['nationality'] = array(
            '#type' => 'select',
            '#title' => 'Национальность',
            '#options' => [
                'RUSSIAN' => 'RUSSIAN',
                'SWEDEN' => 'SWEDEN',
                'FINLAND' => 'FINLAND',
                'CHEZH' => 'CHEZH',
                'CANADA' => 'CANADA',
                'USA' => 'USA',
            ],
            '#empty_option' => '- Выбор -',
            '#required' => TRUE,
        );

        $form['playerType'] = array(
            '#type' => 'select',
            '#title' => 'Тип игрока',
            '#options' => [
                'CENTRAL_STRICKER' => 'CENTRAL_STRICKER',
                'LEFT_STRICKER' => 'LEFT_STRICKER',
                'RIGHT_STRICKER' => 'RIGHT_STRICKER',
                'DEFENDER' => 'DEFENDER',
                'GOALKEEPER' => 'GOALKEEPER',
            ],
            '#empty_option' => '- Выбор -',
            '#required' => TRUE,
        );

        $form['category'] = array(
            '#type' => 'select',
            '#title' => 'Категория',
            '#options' => [
                    't1' => 't1',
                    't2' => 't2',
                    't3' => 't3',
                    't4' => 't4',
                    't5' => 't5',
                    't6' => 't6',
                    't7' => 't7',
                    't8' => 't8',
                    't9' => 't9',
            ],
            '#empty_option' => '- Выбор -',
            '#required' => TRUE,
        );

        $form['image'] = array(
            '#type' => 'textfield',
            '#title' => 'Изображение',
            '#required' => TRUE,
        );

        $form['number'] = array(
            '#type' => 'textfield',
            '#title' => 'Номер',
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

        $form['active'] = array(
            '#type' => 'select',
            '#title' => 'Активный',
            '#options' => [
                    'false' => 'Нет',
                    'true' => 'Да',
            ],
            '#empty_option' => '- Выбор -',
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
		
        $method = 'getLegendaryInfo';
        $data = [];
        $result = HockeyApiLogic::send($method, $data);
        $trainer = [
			'id' => $setting['id'],
			'category' => 'player_t',
			'description' => "Игрок",
			'product' => [[[
				'type' => 'playerTier',
				'nationality' => $setting['nationality'],
				'category' => $setting['category'],
				'playerType' => $setting['playerType'],
				'name' => $setting['name'],
				'surname' => $setting['surname'],
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
				'image' => $setting['image'],
				'number' => $setting['number'],
				'active' => $setting['active'],
				]]],
			'value' => [[
				'type' => 'token',
				'count' => $result['info'][strtoupper($setting['category'])]['cost']
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