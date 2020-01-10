<?php
/**
 * Created by PhpStorm.
 * User: saint
 * Date: 05.03.2018
 * Time: 19:02
 */

namespace Drupal\sofa_page\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\sofa_page\Controller\SofaApiLogic;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;

class CollectionEdit extends FormBase {

    /**
     * {@inheritdoc}.
     */
    // Метод для котороый возвращает ид формы.
    public function getFormId() {
        return 'CollectionEdit_form';
    }

    /**
     * {@inheritdoc}.
     */
    // Вместо hook_form.
    public function buildForm(array $form, FormStateInterface $form_state, $collectionId = '')
    {

		$method  = 'Collections';
		$result = SofaApiLogic::send($method, $result, 'GET', $collectionId);

        $form['#tree'] = TRUE;
        $form['#prefix'] = '<div id="all-fieldset-wrapper">';
        $form['#suffix'] = '</div>';
		

        $form['id'] = array(
            '#type' => 'hidden',
            '#default_value' => $collectionId,
        );
		
        $form['label'] = array(
            '#type' => 'label',
            '#title' => '',
        );
        if (isset($result['id']))
            $form['label']['#title'] = $result['id'];
		
		$form['labelSpace'] = [
			'#type' => 'item',
			'#markup' => $this->t('     '),
		];
		
		if(isset($result['images']['main_texture']['url'])){
			$uri = "http://souz-m.tk".$result['images']['main_texture']['url'];
			$avatar_url1 = "<img src=\"{$uri}\"   width=\"25%\" height=\"25%\">";
		
			$form['label_main_texture'] = array(
				'#type' => 'label',
				'#title' => 'Основная текстура:',
			);
		
			$form['main_texture'] = array(
				'#type' => 'item',
				'#markup' => $avatar_url1,
			);
		
			$form['main_texture_delete'] = array(
				'#type' => 'submit',
				'#name' => 'main_texture_delete',
				'#value' => 'Удалить',
				'#attributes' => [
					'class' => ['btn-danger']
				]
			);
			$form['main_texture_delete']['#limit_validation_errors'] = array();

			$form['main_texture_space'] = [
				'#type' => 'item',
				'#markup' => $this->t('     '),
			];			
		}
		
		if(isset($result['images']['tiles']['url'])){
			$uri = "http://souz-m.tk".$result['images']['tiles']['url'];
			$avatar_url2 = "<img src=\"{$uri}\"   width=\"25%\" height=\"25%\">";
		
			$form['tiles_label'] = array(
				'#type' => 'label',
				'#title' => 'Тайлы:',
			);
			
			$form['tiles'] = array(
				'#type' => 'item',
				'#markup' => $avatar_url2,
			);
			
			$form['tiles_delete'] = array(
				'#type' => 'submit',
				'#name' => 'tiles_delete',
				'#value' => 'Удалить',
				'#attributes' => [
					'class' => ['btn-danger']
				]
			);
			$form['tiles_delete']['#limit_validation_errors'] = array();

			$form['tiles_space'] = [
				'#type' => 'item',
				'#markup' => $this->t('     '),
			];
		}
		
        $form['name'] = array(
            '#type' => 'textfield',
            '#title' => 'Название',
            '#required' => TRUE,
        );
        if (isset($result['name']))
            $form['name']['#default_value'] = $result['name'];

        $form['description'] = array(
            '#type' => 'textfield',
            '#title' => 'Описание',
        );
        if (isset($result['description']))
            $form['description']['#default_value'] = $result['description'];
		
		$form['fabricIds'] = [
            '#type' => 'fieldset',
            '#title' => 'Идентификаторы ткани',
            '#prefix' => '<div id="fabricIds-fieldset-wrapper">',
            '#suffix' => '</div>',
            '#collapsible' => true,
            //'#collapsed'   => true,
        ];
		
			
		$fabric = $form_state->get('fabric');
		if ($fabric === NULL) {
			if(isset($result['fabricIds']))
				$fabric = count($result['fabricIds']);
			else
				$fabric = 0;
			$form_state->set('fabric', $fabric);
		}
		
		for ($j = 0; $j < $fabric; $j++) {
			$form['fabricIds'][$j] = array(
				'#type' => 'textfield',
			);
			if (isset($result['fabricIds'][$j]))
				$form['fabricIds'][$j]['#default_value'] = $result['fabricIds'][$j];
		}

		$form['fabricIds']['action'] = [
			'#type' => 'item',
			'#markup' => $this->t('     '),
		];

		$form['fabricIds']['actions'] = [
			'#type' => 'actions',
		];

		$form['fabricIds']['actions']['add_manuf'] = [
			'#type' => 'submit',
			'#name' => 'add_manuf',
			'#value' => '',
			'#submit' => ['::addOne'],
			'#ajax' => [
				'callback' => '::changeCallback',
				'wrapper' => 'fabricIds-fieldset-wrapper',
			],
			'#limit_validation_errors' => array(), 
			'#attributes' => [
				'class' => ['glyphicon glyphicon-plus']
			],
		];

		if ($fabric > 0) {
			$form['fabricIds']['actions']['remove_manuf'] = [
				'#type' => 'submit',
				'#name' => 'remove_manuf',
				'#value' => '',
				'#submit' => ['::removeOne'],
				'#ajax' => [
					'callback' => '::changeCallback',
					'wrapper' => 'fabricIds-fieldset-wrapper',
				],
				'#limit_validation_errors' => array(), 
				'#attributes' => [
					'class' => ['glyphicon glyphicon-minus']
				],
			];
		}
		
        $form['submit'] = array(
            '#type' => 'submit',
            '#name' => 'submit',
            '#value' => 'Обновить',
            '#attributes' => [
                'class' => ['col-xs-12', 'btn-info']
            ]
        );
        return $form;
    }


    public function changeCallback(array &$form, FormStateInterface $form_state) {
        $trigger = $form_state->getTriggeringElement();
        drupal_set_message(['#type' => 'item', '#markup' => print_r($trigger, true),]);
        if($trigger['#parents'][0] === 'fabricIds')
            return $form['fabricIds'];
        else
            return $form;
    }

    public function typeAjax(FormStateInterface $form_state){
        $trigger = $form_state->getTriggeringElement();
        drupal_set_message(['#type' => 'item', '#markup' => print_r($trigger, true),]);
        if($trigger['#ajax']['wrapper'] === 'fabricIds-fieldset-wrapper'){
            $num_res = $form_state->get('fabric');
            $num_txt = 'fabric';
        }
        return [$num_res, $num_txt];
    }

    public function addOne(array &$form, FormStateInterface $form_state) {
        $arr = $this -> typeAjax($form_state);
        $add_button = $arr[0] + 1;
        $form_state->set($arr[1], $add_button);
        $form_state->setRebuild();
    }

    public function removeOne(array &$form, FormStateInterface $form_state) {
        $arr = $this -> typeAjax($form_state);
        if ($arr[0] > 0) {
            $remove_button = $arr[0] - 1;
            $form_state->set($arr[1], $remove_button);
        }
        $form_state->setRebuild();
    }
	
    /**
     * {@inheritdoc}
     */
    // Вместо hook_form_validate.
    function validateForm(array &$form, FormStateInterface $form_state){
    }

    /**
     * {@inheritdoc}
     */
    // Вместо hook_form_submit.
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $setting = $form_state->getValues();
		$id = $setting['id'];
        $trigger = $form_state->getTriggeringElement();
		drupal_set_message(['#type' => 'item', '#markup' => print_r($trigger, true),]);
		if($trigger['#name'] == 'main_texture_delete') {
			$d = [];
			$r = SofaApiLogic::send('Fabrics', $d, 'DELETE', $_POST['id'].'/textures/main_texture');
			if($r === []) {
				SofaApiLogic::my_goto("Удалена основная текстура");
			} else {
				SofaApiLogic::my_goto(['#type' => 'item', '#markup' => print_r($r, true) ], $_GET);
			}
        }
        elseif($trigger['#name'] == 'normal_map_delete') {
			$d = [];
			$r = SofaApiLogic::send('Fabrics', $d, 'DELETE', $_POST['id'].'/textures/normal_map');
			if($r === []) {
				SofaApiLogic::my_goto("Удалена карта нормалей");
			} else {
				SofaApiLogic::my_goto(['#type' => 'item', '#markup' => print_r($r, true) ], $_GET);
			}
        }
        elseif($trigger['#attributes']['button_name'] == 'submit') {
			$result = [
				'id' => $setting['id'],
				'name' => $setting['name'],
				'description' => $setting['description'],
				'fabricIds' => [],
			];

			$fabric =  $form_state->get('fabric');
			$array_fabric = array();
			for ($j = 0; $j < $fabric; $j++) {
				$array_fabric[] = $setting['fabricIds'][$j];
			}
			$result['fabricIds'] = $array_fabric;

			
			$method  = 'Fabrics';
			$result = SofaApiLogic::send($method, $result, 'PUT', $setting['id']);
			if($result === []){
				drupal_set_message('Успешно');
				//drupal_set_message(['#type' => 'item', '#markup' => print_r($result, true),]);
			} else {
				drupal_set_message('Не обновлено', 'error');
			}
		}
    }

}