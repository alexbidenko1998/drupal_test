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

class LegendaryPlayerEdit extends FormBase {

    /**
     * {@inheritdoc}.
     */
    // Метод для котороый возвращает ид формы.
    public function getFormId() {
        return 'LegendaryPlayerEdit_form';
    }

    /**
     * {@inheritdoc}.
     */
    // Вместо hook_form.
    public function buildForm(array $form, FormStateInterface $form_state, $playerId = '')
    {
        
        $error = ['#type' => 'item', '#markup' => 'Error',];

        $method = 'getJsonDocument';
        $data = ['name' => 'legendary'];
        $result = HockeyApiLogic::send($method, $data);

        $i = HockeyApiLogic::filterNumber('id', $playerId, $result);

        $setting = $form_state->getValues();
		
        $form['i'] = array(
            '#type' => 'hidden',
            '#default_value' => $i,
        );
        $form['result'] = array(
            '#type' => 'hidden',
            '#default_value' => json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        );

        $form['name'] = array(
            '#type' => 'label',
            '#title' => 'Имя',
        );
        if (isset($result[$i]['name']))
			$form['name']['#title'] = $result[$i]['name'].'     '.$result[$i]['surname'];
		
        /*$form['number'] = array(
            '#type' => 'textfield',
            '#title' => 'Фамилия',
            '#required' => TRUE,
        );*/

        $form['nationality'] = array(
            '#type' => 'select',
            '#title' => 'Национальность',
            '#options' => [
                'russian' => 'RUSSIAN',
                'sweden' => 'SWEDEN',
                'finland' => 'FINLAND',
                'chezh' => 'CHEZH',
                'canada' => 'CANADA',
                'usa' => 'USA',
            ],
            '#empty_option' => '- Выбор -',
            '#required' => TRUE,
        );
        if (isset($result[$i]['nationality']))
			$form['nationality']['#default_value'] = $result[$i]['nationality'];

        $form['image'] = array(
            '#type' => 'textfield',
            '#title' => 'Изображение',
            '#required' => TRUE,
        );
        if (isset($result[$i]['image']))
			$form['image']['#default_value'] = $result[$i]['image'];

        $form['number'] = array(
            '#type' => 'textfield',
            '#title' => 'Номер',
        );
        if (isset($result[$i]['number']))
			$form['number']['#default_value'] = $result[$i]['number'];

        $form['textNameRus'] = array(
            '#type' => 'textfield',
            '#title' => 'Имя в описании (Русский)',
            '#required' => TRUE,
        );
        if (isset($result[$i]['textName']['RUSSIAN']))
			$form['textNameRus']['#default_value'] = $result[$i]['textName']['RUSSIAN'];

        $form['textNameEng'] = array(
            '#type' => 'textfield',
            '#title' => 'Имя в описании (Английский)',
            '#required' => TRUE,
        );
        if (isset($result[$i]['textName']['ENGLISH']))
			$form['textNameEng']['#default_value'] = $result[$i]['textName']['ENGLISH'];

        $form['textNameGer'] = array(
            '#type' => 'textfield',
            '#title' => 'Имя в описании (Немецкий)',
            '#required' => TRUE,
        );
        if (isset($result[$i]['textName']['GERMAN']))
			$form['textNameGer']['#default_value'] = $result[$i]['textName']['GERMAN'];

        $form['textRus'] = array(
            '#type' => 'textarea',
            '#title' => 'Описание (Русский)',
            '#required' => TRUE,
        );
        if (isset($result[$i]['text']['RUSSIAN']))
			$form['textRus']['#default_value'] = $result[$i]['text']['RUSSIAN'];

        $form['textEng'] = array(
            '#type' => 'textarea',
            '#title' => 'Описание (Английский)',
            '#required' => TRUE,
        );
        if (isset($result[$i]['text']['ENGLISH']))
			$form['textEng']['#default_value'] = $result[$i]['text']['ENGLISH'];

        $form['textGer'] = array(
            '#type' => 'textarea',
            '#title' => 'Описание (Немецкий)',
            '#required' => TRUE,
        );
        if (isset($result[$i]['text']['GERMAN']))
			$form['textGer']['#default_value'] = $result[$i]['text']['GERMAN'];

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
		
        if (isset($result[$i]['active'])){
			$form['active']['#default_value'] = 'false';
			if($result[$i]['active'] == 1)
				$form['active']['#default_value'] = 'true';
			if($result[$i]['active'] == '1')
				$form['active']['#default_value'] = 'true';
			if($result[$i]['active'] == 'true')
				$form['active']['#default_value'] = 'true';
		}
           

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
		
        $result[$i]['nationality'] = $setting['nationality'];
        $result[$i]['image'] = $setting['image'];
        $result[$i]['number'] = $setting['number'];
        $result[$i]['text']['RUSSIAN'] = $setting['textRus'];
        $result[$i]['text']['ENGLISH'] = $setting['textEng'];
        $result[$i]['text']['GERMAN'] = $setting['textGer'];
        $result[$i]['textName']['RUSSIAN'] = $setting['textNameRus'];
        $result[$i]['textName']['ENGLISH'] = $setting['textNameEng'];
        $result[$i]['textName']['GERMAN'] = $setting['textNameGer'];
        $result[$i]['active'] = $setting['active'];
		
		
        $data    = ['name' => 'legendary', 'json' => $result];
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