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

class LegendaryPlayerTEdit extends FormBase {

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
    public function buildForm(array $form, FormStateInterface $form_state, $playerId = '')
    {
        
        $error = ['#type' => 'item', '#markup' => 'Error',];

        $method = 'getJsonDocument';
        $data = ['name' => 'shop'];
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
        if (isset($result[$i]['product'][0][0]['name']))
			$form['name']['#title'] = $result[$i]['product'][0][0]['name'].'     '.$result[$i]['product'][0][0]['surname'];

        /*$form['name'] = array(
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
			$form['surname']['#default_value'] = $result[$i]['product'][0][0]['surname'];*/
		
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
        if (isset($result[$i]['product'][0][0]['nationality']))
			$form['nationality']['#default_value'] = $result[$i]['product'][0][0]['nationality'];

        $form['image'] = array(
            '#type' => 'textfield',
            '#title' => 'Изображение',
            '#required' => TRUE,
        );
        if (isset($result[$i]['product'][0][0]['image']))
			$form['image']['#default_value'] = $result[$i]['product'][0][0]['image'];

        $form['number'] = array(
            '#type' => 'textfield',
            '#title' => 'Номер',
        );
        if (isset($result[$i]['product'][0][0]['number']))
			$form['number']['#default_value'] = $result[$i]['product'][0][0]['number'];

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
        if (isset($result[$i]['product'][0][0]['active'])){
			$form['active']['#default_value'] = 'false';
			if($result[$i]['product'][0][0]['active'] == 1)
				$form['active']['#default_value'] = 'true';
			if($result[$i]['product'][0][0]['active'] == '1')
				$form['active']['#default_value'] = 'true';
			if($result[$i]['product'][0][0]['active'] == 'true')
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
		
        $method = 'getLegendaryInfo';
        $data = [];
        $legend = HockeyApiLogic::send($method, $data);
		
        $result[$i]['product'][0][0]['nationality'] = $setting['nationality'];
        $result[$i]['product'][0][0]['image'] = $setting['image'];
        $result[$i]['product'][0][0]['text']['RUSSIAN'] = $setting['textRus'];
        $result[$i]['product'][0][0]['text']['ENGLISH'] = $setting['textEng'];
        $result[$i]['product'][0][0]['text']['GERMAN'] = $setting['textGer'];
        $result[$i]['product'][0][0]['textName']['RUSSIAN'] = $setting['textNameRus'];
        $result[$i]['product'][0][0]['textName']['ENGLISH'] = $setting['textNameEng'];
        $result[$i]['product'][0][0]['textName']['GERMAN'] = $setting['textNameGer'];
        $result[$i]['product'][0][0]['active'] = $setting['active'];
		
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