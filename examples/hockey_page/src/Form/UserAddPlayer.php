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

class UserAddPlayer extends FormBase {
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


        $form['userId'] = array(
            '#type' => 'hidden',
            '#default_value' => $userId,
        );


        $form['type'] = array(
            '#type' => 'select',
            '#title' => 'Тип игрока',
            '#options' => [
                'CENTRAL_STRICKER' => 'Центральный нападающий',
                'LEFT_STRICKER' => 'Левый нападающий',
                'RIGHT_STRICKER' => 'Правый нападающий',
                'DEFENDER' => 'Защитник',
                'GOALKEEPER' => 'Вратарь',
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
            '#default_value' => 27,
            '#required' => TRUE,
        );

        $form['legendary'] = array(
            '#title' => 'Легендарный',
            '#type' => 'checkbox',
            '#default_value' => 0,
        );

        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => 'Добавить игрока',
            '#attributes' => [
                'class' => ['col-xs-12']
            ]
        );

        $form['#prefix'] = '<div class="row"><div class="col-xs-12">';
        $form['#suffix'] = '</div></div>';

        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        $method = 'accountAddPlayer';

        if($form_state->getValue('legendary') === 1)
            $legendary = TRUE;
        else
            $legendary = FALSE;
        $data = [
            'accountId' =>  $form_state->getValue('userId'),
            'type' => $form_state->getValue('type'),
            'level' => $form_state->getValue('level'),
            'legendary' => $legendary,
        ];

        $result = HockeyApiLogic::send($method, $data, 'object');

        if($result !== array()){
            drupal_set_message('Успешно');
             //drupal_set_message(['#type' => 'item', '#markup' => print_r($result, true),]);
        } else {
            drupal_set_message('Ошибка');
        }
    }

}