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

class SofaAdd extends FormBase {

    /**
     * {@inheritdoc}.
     */
    // Метод для котороый возвращает ид формы.
    public function getFormId() {
        return 'SofaAdd_form';
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
		
		$form['import_model'] = [
		  '#title' => '3D Модели',
		  '#type' => 'dropzonejs',
		  '#dropzone_description' => 'Загрузите 3D Модели',
		  '#max_filesize' => '50М',
		  '#extensions' => 'obj',
		];
		
		$form['import_gif'] = [
		  '#title' => 'Gif',
		  '#type' => 'dropzonejs',
		  '#dropzone_description' => 'Gif',
		  '#max_filesize' => '50М',
		  '#extensions' => 'gif',
		];
		
        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => 'Загрузить',
            '#attributes' => [
                'class' => ['col-xs-12', 'btn-info']
            ]
        );
        return $form;
    }





	
    function validateForm(array &$form, FormStateInterface $form_state){
    }

    /**
     * {@inheritdoc}
     */
    // Вместо hook_form_submit.
    public function submitForm(array &$form, FormStateInterface $form_state) {
		
        $setting = $form_state->getValues();
	

		$method  = 'Sofas';
		$import_csv = $form_state->getValue('import_csv');
		$data = SofaApiLogic::csvtoarray($import_csv['uploaded_files'][0]['path'], ',');
		
		$error = 0;
		foreach($data as $item){
			if($item['Универсальный?'] === 'Да')
				$multipurpose = true;
			else
				$multipurpose = false;
			
			$result = [
				'id' => $item['Название файла модели НОВОЕ'],
				'name' => $item['Название модели'],
				'description' => $item['Описание'] ?? NULL,
				'manufacturerId' => $item['Фабрика'] ?? NULL,
				'promoteCollectionId' => $item['Коллекция'] ?? NULL,
				//'defaultFabricId' => $item['defaultFabricId'],
				'transformationType' => $item['Механизм трансформации'] ?? NULL,
				'typeOfFurniture' => $item['Тип мебели'] ?? NULL,
				'parts' => [],
				'multipurpose' => $multipurpose,
				'furnitureForm' => $item['Форма мебели'] ?? NULL,
				'furnitureGroup' => $item['Мебельная группа'] ?? NULL,
				'furnitureStyle' => $item['Стиль'] ?? NULL,
				'additionalProperties' => [],
				//'length' => $item['Длина'] ?? NULL,
				//'width' => $item['Ширина'] ?? NULL,
				//'height' => $item['Высота'] ?? NULL,
				//'bedLength' => $item['Длина спального места'] ?? NULL,
				//'bedWidth' => $item['Ширина спального места'] ?? NULL,
				//'bedHeight' => $item['Высота спального места'] ?? NULL,
				//'seatLength' => $item['Длина посадочного места'],
				//'seatWidth' => $item['Ширина посадочного места'],
				//'seatHeight' => $item['Высота посадочного места'],
			];
	
			if($item['Длина'] != '')
				$result['length'] = (float)$item['Длина'];
			if($item['Ширина'] != '')
				$result['width'] = (float)$item['Ширина'];
			if($item['Высота'] != '')
				$result['height'] = (float)$item['Высота'];
			if($item['Длина спального места'] != '')
				$result['bedLength'] = (float)$item['Длина спального места'];
			if($item['Ширина спального места'] != '')
				$result['bedWidth'] = (float)$item['Ширина спального места'];
			if($item['Высота спального места'] != '')
				$result['bedHeight'] = (float)$item['Высота спального места'];
			
			$result = SofaApiLogic::send($method, $result, 'POST');
		
			if($result != []){
				$error = 1;
				drupal_set_message('Не создано '.$item['Название модели'], 'error');
			}
			
		}
		
        if($error === 0){
            drupal_set_message('Успешно');

			$import_model = $form_state->getValue('import_model');
			$data = SofaApiLogic::csvtoarray(['path'], ',');
			foreach($import_model['uploaded_files'] as $model){
				$absolute_path = \Drupal::service('file_system')->realpath($model['path']);
				$ext = pathinfo($absolute_path, PATHINFO_EXTENSION);

				$data = [
					[
						'name'     => 'file',
						'contents' => fopen($absolute_path, 'r'),
					]
				];
				
				$name = explode('.',$model['filename']);
				$result = SofaApiLogic::send($method, $data, 'POST_MULT', $name[0].'/model_file', 'array');
				if($result === []){
					
				} else {
					drupal_set_message('Изображение '.$model['filename'].' не загружено', 'error');
				}
			}
			
		
		}
   
   }

}