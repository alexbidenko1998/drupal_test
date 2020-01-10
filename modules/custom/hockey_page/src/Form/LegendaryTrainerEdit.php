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

class LegendaryTrainerEdit extends FormBase {

    /**
     * {@inheritdoc}.
     */
    // Метод для котороый возвращает ид формы.
    public function getFormId() {
        return 'LegendaryTrainerEdit_form';
    }

    /**
     * {@inheritdoc}.
     */
    // Вместо hook_form.
    public function buildForm(array $form, FormStateInterface $form_state, $trainerId = '')
    {
        
        $error = ['#type' => 'item', '#markup' => 'Error',];

        $method = 'getJsonDocument';
        $data = ['name' => 'shop'];
        $result = HockeyApiLogic::send($method, $data);

        $i = HockeyApiLogic::filterNumber('id', $trainerId, $result);

        $setting = $form_state->getValues();
		
			
        $form['i'] = array(
            '#type' => 'hidden',
            '#default_value' => $i,
        );
        $form['result'] = array(
            '#type' => 'hidden',
            '#default_value' => json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        );
		
        $form['id'] = array(
            '#type' => 'textfield',
            '#title' => 'id (покупки заведеной в Google и Apple)',
            '#required' => TRUE,
        );
        if (isset($result[$i]['id']))
			$form['id']['#default_value'] = $result[$i]['id'];

        $form['name'] = array(
            '#type' => 'textfield',
            '#title' => 'Имя',
            '#required' => TRUE,
        );
        if (isset($result[$i]['product'][0][0]['name']))
			$form['name']['#default_value'] = $result[$i]['product'][0][0]['name'];

        $form['surname'] = array(
            '#type' => 'textfield',
            '#title' => 'Фамилия',
            '#required' => TRUE,
        );
        if (isset($result[$i]['product'][0][0]['surname']))
			$form['surname']['#default_value'] = $result[$i]['product'][0][0]['surname'];
		
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
        if (isset($result[$i]['product'][0][0]['trainerType']))
			$form['trainerType']['#default_value'] = $result[$i]['product'][0][0]['trainerType'];

        $form['level'] = array(
            '#type' => 'textfield',
            '#title' => 'Уровень',
            '#attributes' => array(
                ' type' => 'number',
            ),
            '#required' => TRUE,
        );
        if (isset($result[$i]['product'][0][0]['level']))
			$form['level']['#default_value'] = $result[$i]['product'][0][0]['level'];
		
        $form['image'] = array(
            '#type' => 'textfield',
            '#title' => 'Изображение',
            '#required' => TRUE,
        );
        if (isset($result[$i]['product'][0][0]['image']))
			$form['image']['#default_value'] = $result[$i]['product'][0][0]['image'];

        $form['attack'] = array(
            '#type' => 'textfield',
            '#title' => 'Бонус - Атака',
            '#attributes' => array(
                ' type' => 'number',
            ),
            '#required' => TRUE,
        );
        if (isset($result[$i]['product'][0][0]['trainerBonus']['attack']))
			$form['attack']['#default_value'] = $result[$i]['product'][0][0]['trainerBonus']['attack'];

        $form['defend'] = array(
            '#type' => 'textfield',
            '#title' => 'Бонус - Защита',
            '#attributes' => array(
                ' type' => 'number',
            ),
            '#required' => TRUE,
        );
        if (isset($result[$i]['product'][0][0]['trainerBonus']['defend']))
			$form['defend']['#default_value'] = $result[$i]['product'][0][0]['trainerBonus']['defend'];

        $form['attackOnEnemySide'] = array(
            '#type' => 'textfield',
            '#title' => 'Бонус - Атака на поле противника',
            '#attributes' => array(
                ' type' => 'number',
            ),
            '#required' => TRUE,
        );
        if (isset($result[$i]['product'][0][0]['trainerBonus']['attackOnEnemySide']))
			$form['attackOnEnemySide']['#default_value'] = $result[$i]['product'][0][0]['trainerBonus']['attackOnEnemySide'];
		
        $form['defendOnAllySide'] = array(
            '#type' => 'textfield',
            '#title' => 'Бонус - Защита на своем поле',
            '#attributes' => array(
                ' type' => 'number',
            ),
            '#required' => TRUE,
        );
        if (isset($result[$i]['product'][0][0]['trainerBonus']['defendOnAllySide']))
			$form['defendOnAllySide']['#default_value'] = $result[$i]['product'][0][0]['trainerBonus']['defendOnAllySide'];

        $form['more'] = array(
            '#type' => 'textfield',
            '#title' => 'Бонус - Большинство',
            '#attributes' => array(
                ' type' => 'number',
            ),
            '#required' => TRUE,
        );
        if (isset($result[$i]['product'][0][0]['trainerBonus']['more']))
			$form['more']['#default_value'] = $result[$i]['product'][0][0]['trainerBonus']['more'];

        $form['less'] = array(
            '#type' => 'textfield',
            '#title' => 'Бонус - Меньшиство',
            '#attributes' => array(
                ' type' => 'number',
            ),
            '#required' => TRUE,
        );
        if (isset($result[$i]['product'][0][0]['trainerBonus']['less']))
			$form['less']['#default_value'] = $result[$i]['product'][0][0]['trainerBonus']['less'];

        $form['textNameRus'] = array(
            '#type' => 'textfield',
            '#title' => 'Имя в описании (Русский)',
            '#required' => TRUE,
        );
        if (isset($result[$i]['product'][0][0]['textName']['RUSSIAN']))
			$form['textNameRus']['#default_value'] = $result[$i]['product'][0][0]['textName']['RUSSIAN'];

        $form['textNameEng'] = array(
            '#type' => 'textfield',
            '#title' => 'Имя в описании (Английский)',
            '#required' => TRUE,
        );
        if (isset($result[$i]['product'][0][0]['textName']['ENGLISH']))
			$form['textNameEng']['#default_value'] = $result[$i]['product'][0][0]['textName']['ENGLISH'];

        $form['textNameGer'] = array(
            '#type' => 'textfield',
            '#title' => 'Имя в описании (Немецкий)',
            '#required' => TRUE,
        );
        if (isset($result[$i]['product'][0][0]['textName']['GERMAN']))
			$form['textNameGer']['#default_value'] = $result[$i]['product'][0][0]['textName']['GERMAN'];

        $form['textRus'] = array(
            '#type' => 'textarea',
            '#title' => 'Описание (Русский)',
            '#required' => TRUE,
        );
        if (isset($result[$i]['product'][0][0]['text']['RUSSIAN']))
			$form['textRus']['#default_value'] = $result[$i]['product'][0][0]['text']['RUSSIAN'];

        $form['textEng'] = array(
            '#type' => 'textarea',
            '#title' => 'Описание (Английский)',
            '#required' => TRUE,
        );
        if (isset($result[$i]['product'][0][0]['text']['ENGLISH']))
			$form['textEng']['#default_value'] = $result[$i]['product'][0][0]['text']['ENGLISH'];

        $form['textGer'] = array(
            '#type' => 'textarea',
            '#title' => 'Описание (Немецкий)',
            '#required' => TRUE,
        );
        if (isset($result[$i]['product'][0][0]['text']['GERMAN']))
			$form['textGer']['#default_value'] = $result[$i]['product'][0][0]['text']['GERMAN'];


        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => '  Изменить',
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
        $result    = $form_state->getValue('result');
        $result = (array) json_decode ($result, true);
        $i    = $form_state->getValue('i');
        $setting = $form_state->getValues();
		
        $result[$i]['id'] = $setting['id'];
        $result[$i]['product'][0][0]['trainerType'] = $setting['trainerType'];
        $result[$i]['product'][0][0]['level'] = $setting['level'];
        $result[$i]['product'][0][0]['name'] = $setting['name'];
        $result[$i]['product'][0][0]['surname'] = $setting['surname'];
        $result[$i]['product'][0][0]['trainerBonus']['attack'] = $setting['attack'];
        $result[$i]['product'][0][0]['trainerBonus']['defend'] = $setting['defend'];
        $result[$i]['product'][0][0]['trainerBonus']['attackOnEnemySide'] = $setting['attackOnEnemySide'];
        $result[$i]['product'][0][0]['trainerBonus']['defendOnAllySide'] = $setting['defendOnAllySide'];
        $result[$i]['product'][0][0]['trainerBonus']['more'] = $setting['more'];
        $result[$i]['product'][0][0]['trainerBonus']['less'] = $setting['less'];
        $result[$i]['product'][0][0]['text']['RUSSIAN'] = $setting['textRus'];
        $result[$i]['product'][0][0]['text']['ENGLISH'] = $setting['textEng'];
        $result[$i]['product'][0][0]['text']['GERMAN'] = $setting['textGer'];
        $result[$i]['product'][0][0]['textName']['RUSSIAN'] = $setting['textNameRus'];
        $result[$i]['product'][0][0]['textName']['ENGLISH'] = $setting['textNameEng'];
        $result[$i]['product'][0][0]['textName']['GERMAN'] = $setting['textNameGer'];
        $result[$i]['value'][0]['productId'] = $setting['id'];
		
        $data    = ['name' => 'shop', 'json' => $result];
        $method  = 'setJsonDocument';
        $result = HockeyApiLogic::send($method, $data);
        if($result === array()){
            drupal_set_message('Успешно');
            //drupal_set_message(['#type' => 'item', '#markup' => print_r($result, true),]);
        } else {
            drupal_set_message('Не обновлено', 'error');
        }
    }

}