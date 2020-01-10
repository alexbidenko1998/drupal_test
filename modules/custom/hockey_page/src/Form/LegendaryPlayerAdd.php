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

class LegendaryPlayerAdd extends FormBase {

    /**
     * {@inheritdoc}.
     */
    // Метод для котороый возвращает ид формы.
    public function getFormId() {
        return 'LegendaryPlayerAdd_form';
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
                    'r1' => 'r1',
                    'r2' => 'r2',
                    'r3' => 'r3',
                    'r4' => 'r4',
                    'r5' => 'r5',
                    'r6' => 'r6',
                    'r0' => 'r0',
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
        $result = [
			'nationality' => $setting['nationality'],
			'playerType' => $setting['playerType'],
			'tier' => 'PURCHASE',
			'category' => $setting['category'],
			'name'=> $setting['name'],
			'surname' => $setting['surname'],
			'image' => $setting['image'],
			'number' => $setting['number'],
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
			'active' => $setting['active'],
		];
		
        $method  = 'addLegendaryPlayer';
        $result = HockeyApiLogic::send($method, $result);
        if($result === array()){
            drupal_set_message('Успешно');
            //drupal_set_message(['#type' => 'item', '#markup' => print_r($result, true),]);
        } else {
            drupal_set_message('Не обновлено', 'error');
        }
    }

}