<?php
/**
 * Created by PhpStorm.
 * User: saint
 * Date: 26.02.2018
 * Time: 12:36
 */

namespace Drupal\hockey_page\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\hockey_page\Controller\HockeyApiLogic;

class SettingsEditMenuBlockForm extends FormBase {
    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'settings_edit_menu_block_form';
    }
    /**
     * {@inheritdoc}
     * Form
     */
    public function buildForm(array $form, FormStateInterface $form_state) {

        $empty = '�� ������� �������� ������';

        $method = 'documentNames';
        $data = [];

        $result = HockeyApiLogic::send($method, $data);
        if ($result == NULL) {
            return [
                '#type' => 'item',
                '#markup' => $empty,
            ];
        }

        $form = [
            '#type' => 'markup',
        ];


        $form['table'] = array(
            '#type' => 'table',
            '#prefix' => '<div class="scroll-pane"><div class="col-xs-12"><div class="row">', '#suffix' => '</div></div></div>',
            '#attached' => array(
                'library' => array(
                    'hockey_page/scrollmenu'
                ),
            ),
        );

        $current_path = \Drupal::service('path.current')->getPath();
        $c_path = explode('/', $current_path);
        $arr = [];

        foreach($result['names'] as $item)  {

            $Id = $item;

            $objectUrl = '<a href="/'.$c_path[1].'/'.$Id.'/'.$c_path[3].'">'.$Id.'</a>';

            $form['table'][] = [
                'links' => [
                    '#type' => 'item',
                    '#markup' => $objectUrl,
                ],
            ];
        }

        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => 'Перезагрузить конфиги',
            '#attributes' => [
                'class' => ['col-xs-12']
            ]
        );

        return $form;
    }
    /**
     * {@inheritdoc}
     * Submit
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $method = 'reloadConfig';
        $data = [];

        $result = HockeyApiLogic::send($method, $data, 'object');

        if($result !== array()){
            drupal_set_message('Успешно');
            // drupal_set_message(['#type' => 'item', '#markup' => print_r($result, true),]);
        } else {
            drupal_set_message('Ошибка');
        }
    }
}