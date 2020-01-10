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

class FabricAdd extends FormBase {

    /**
     * {@inheritdoc}.
     */
    // Метод для котороый возвращает ид формы.
    public function getFormId() {
        return 'FabricAdd_form';
    }

    /**
     * {@inheritdoc}.
     */
    // Вместо hook_form.
    public function buildForm(array $form, FormStateInterface $form_state)
    {
		
		$form['import_csv'] = [
		  '#title' => 'CSV файл',
		  '#type' => 'dropzonejs',
		  '#dropzone_description' => 'Загрузите CSV файл',
		  '#max_filesize' => '10M',
		  '#extensions' => 'csv',
		];
		
		$form['import_main_texture'] = [
		  '#title' => 'Основные текстуры',
		  '#type' => 'dropzonejs',
		  '#dropzone_description' => 'Загрузите основные текстуры',
		  '#max_filesize' => '50М',
		  '#extensions' => 'jpg',
		];
		
		$form['import_normal_map'] = [
		  '#title' => 'Карты нормалей',
		  '#type' => 'dropzonejs',
		  '#dropzone_description' => 'Загрузите карты нормалей',
		  '#max_filesize' => '50М',
		  '#extensions' => 'jpg',
		];
		
		$form['import_metallic_gloss_map'] = [
		  '#title' => 'Карты металлических отражений',
		  '#type' => 'dropzonejs',
		  '#dropzone_description' => 'Загрузите карты металлических отражений',
		  '#max_filesize' => '50М',
		  '#extensions' => 'jpg',
		];
		
		
	
        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => 'Создать',
            '#attributes' => [
                'class' => ['col-xs-12', 'btn-info']
            ]
        );
        return $form;
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
drupal_set_message(['#type' => 'item', '#markup' => print_r($setting, true),]);
/*
		$id = $setting['id'];
        $result = [
			'id' => $setting['id'],
			'name' => $setting['name'],
			'description' => $setting['description'],
			'productType' => $setting['productType'],
			'fabricType' => $setting['fabricType'],
			'manufacturerIds' => [],
			'similarFabricIds' => [],
			'companionFabricIds' => [],
			'showInCatalog' => $setting['showInCatalog'],
			'tags' => [],
			'colors' => [],
			'producingCountry' => $setting['producingCountry'],
			'designTypeName' => $setting['designTypeName'],
			'rapportSizeDescription' => [
				'width' => $setting['rapportSizeDescription']['width'],
				'height' => $setting['rapportSizeDescription']['height'],
			],
			'fabricRollWidth' => [
				'width' => $setting['fabricRollWidth']['width'],
				'error' => $setting['fabricRollWidth']['error'],
			],
			'materialAbrasionResistance' => [
				'count' => $setting['materialAbrasionResistance']['count'],
				'sign' => $setting['materialAbrasionResistance']['sign'],
			],
			'materialDensity' => [
				'gram' => $setting['materialDensity']['gram'],
				'error' => $setting['materialDensity']['error'],
			],
			'materialDirection' => $setting['materialDirection'],
			'materials' => [],
			'careList' => [],
			'detailsList' => [],
			'storeUnits' => $setting['storeUnits'],
			'storePrice' => $setting['storePrice'],
			'storeMinimumOrder' => $setting['storeMinimumOrder'],
			'storeAvailable' => $setting['storeAvailable'],
			'textureConfig' => [
				'glossiness' => $setting['textureConfig']['glossiness'],
				'size' => [
					'x' => $setting['textureConfig']['size']['x'],
					'y' => $setting['textureConfig']['size']['y'],
					'z' => $setting['textureConfig']['size']['z'],
				],
				'asSteady' => $setting['textureConfig']['asSteady'],
			],
		];

		$manuf =  $form_state->get('manuf');
		$array_manuf = array();
		for ($j = 0; $j < $manuf; $j++) {
			$array_manuf[] = $setting['manufacturerIds'][$j];
		}
		$result['manufacturerIds'] = $array_manuf;

		$similar =  $form_state->get('similar');
		$array_similar = array();
		for ($j = 0; $j < $similar; $j++) {
			$array_similar[] = $setting['similarFabricIds'][$j];
		}
		$result['similarFabricIds'] = $array_similar;

		$comp =  $form_state->get('comp');
		$array_comp = array();
		for ($j = 0; $j < $comp; $j++) {
			$array_comp[] = $setting['companionFabricIds'][$j];
		}
		$result['companionFabricIds'] = $array_comp;

		$tags =  $form_state->get('tags');
		$array_tags = array();
		for ($j = 0; $j < $tags; $j++) {
			$array_tags[] = $setting['tags'][$j];
		}
		$result['tags'] = $array_tags;

		$colors =  $form_state->get('colors');
		$array_colors = array();
		for ($j = 0; $j < $colors; $j++) {
			$array_colors[] = $setting['colors'][$j];
		}
		$result['colors'] = $array_colors;

		$material =  $form_state->get('material');
		$array_material = array();
		for ($j = 0; $j < $material; $j++) {
			$array_material[] = [
				'name' => $setting['materials'][$j]['name'],
				'percent' => $setting['materials'][$j]['percent']
			];
		}
		$result['materials'] = $array_material;

		$care =  $form_state->get('care');
		$array_care = array();
		for ($j = 0; $j < $care; $j++) {
			$array_care[] = $setting['careList'][$j];
		}
		$result['careList'] = $array_care;

		$details =  $form_state->get('details');
		$array_details = array();
		for ($j = 0; $j < $details; $j++) {
			$array_details[] = $setting['detailsList'][$j];
		}
		$result['detailsList'] = $array_details;
		
        $method  = 'Fabrics';
        $result = SofaApiLogic::send($method, $result, 'POST');
		drupal_set_message(['#type' => 'item', '#markup' => print_r($result, true),]);
        if($result === []){
            drupal_set_message('Успешно');
		
			$data = [];

			$main_texture = $form_state->getValue('main_texture');	
			if($main_texture != NULL){
				$oNewFile = \Drupal\file\Entity\File::load(reset($main_texture));
				$fileUrl = $oNewFile->getFileUri();
				$absolute_path = \Drupal::service('file_system')->realpath($fileUrl);
				$ext = pathinfo($absolute_path, PATHINFO_EXTENSION);
				$data[] = [
						'name'     => 'main_texture',
						'contents' => fopen($absolute_path, 'r'),
					];
			}
			$normal_map = $form_state->getValue('normal_map');
			if($normal_map != NULL){
				$oNewFile = \Drupal\file\Entity\File::load(reset($normal_map));
				$fileUrl = $oNewFile->getFileUri();
				$absolute_path = \Drupal::service('file_system')->realpath($fileUrl);
				$ext = pathinfo($absolute_path, PATHINFO_EXTENSION);
				$data[] = [
						'name'     => 'normal_map',
						'contents' => fopen($absolute_path, 'r'),
					];
			}
			$metallic_gloss_map = $form_state->getValue('metallic_gloss_map');
			if($metallic_gloss_map != NULL){
				$oNewFile = \Drupal\file\Entity\File::load(reset($metallic_gloss_map));
				$fileUrl = $oNewFile->getFileUri();
				$absolute_path = \Drupal::service('file_system')->realpath($fileUrl);
				$ext = pathinfo($absolute_path, PATHINFO_EXTENSION);
				$data[] = [
						'name'     => 'metallic_gloss_map',
						'contents' => fopen($absolute_path, 'r'),
					];
			}
			
			if($data != []){
				$result = SofaApiLogic::send($method, $data, 'POST_MULT', $id.'/textures', 'array');
				if($result === []){
				} else {
					drupal_set_message('Текстуры не загружены', 'error');
				}
			}
            //drupal_set_message(['#type' => 'item', '#markup' => print_r($result, true),]);
        } else {
            drupal_set_message('Не обновлено', 'error');
        }
		*/
    }

}