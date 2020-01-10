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

class SendPush extends FormBase {
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


        $form['push'] = [
            '#type' => 'details',
            '#title' => 'Сообщение',
            '#open' => FALSE,
        ];

        $form['push']['textRus'] = array(
            '#type' => 'textfield',
            '#title' => 'Текст русский',
        );

        $form['push']['textEng'] = array(
            '#type' => 'textfield',
            '#title' => 'Текст английский',
        );

        $form['push']['timer'] = array(
            '#type' => 'textfield',
            '#title' => 'Время (в минутах, если не нужно то 0)',
            '#attributes' => array(
                ' type' => 'number',
            ),
            '#default_value' => 0,
        );

        $form['push']['submit'] = array(
            '#type' => 'submit',
            '#value' => 'Отправить',
            '#attributes' => [
                'class' => ['col-xs-12']
            ],
        );

        $form['#prefix'] = '<div class="row"><div class="col-xs-12">';
        $form['#suffix'] = '</div></div>';

        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        $method = 'sendMessage';
        $push = $form_state->getValues();
        if($push['timer'] === 0) {
            $data = [
                'textRus' => $push['textRus'],
                'textEng' => $push['textEng'],
            ];
        }
        else {

            $data = [
                'textRus' => $push['textRus'],
                'textEng' => $push['textEng'],
                'timer' => $push['timer'],
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