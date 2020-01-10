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
use Drupal\Core\Render\Element;

class CollectionAddImage extends FormBase {
    /**
     * {@inheritdoc}.
     */
    // Метод для котороый возвращает ид формы.
    public function getFormId() {
        return 'CollectionAddImage_form';
    }

    /**
     * {@inheritdoc}.
     */
    // Вместо hook_form.
    public function buildForm(array $form, FormStateInterface $form_state, $collectionId = '') {

		
        $form['id'] = array(
            '#type' => 'hidden',
            '#default_value' => $collectionId,
        );

        $validators = array(
        );
		
        $form['main_texture'] = array(
            '#type' => 'managed_file',
            '#name' => 'main_texture',
            '#title' => 'Основная текстура (png, jpg, jpeg)',
            '#size' => 20,
            '#description' => t('PNG, JPG, JPEG format only'),
            '#upload_validators' => $validators,
            '#upload_location' => 'public://image_file/',
        );

        $form['main_texture']['#limit_validation_errors'] = array();
		
        $form['tiles'] = array(
            '#type' => 'managed_file',
            '#name' => 'tiles',
            '#title' => 'Тайлы (png, jpg, jpeg)',
            '#size' => 20,
            '#description' => t('PNG, JPG, JPEG format only'),
            '#upload_validators' => $validators,
            '#upload_location' => 'public://image_file/',
        );

        $form['tiles']['#limit_validation_errors'] = array();

        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => '   Изменить',
            '#attributes' => [
                'class'=>['col-xs-12', 'btn-success', 'btn-reload','glyphicon glyphicon-save'],
            ]
        );

        $form['#prefix'] = '<div class="row"><div class="col-xs-12">';
        $form['#suffix'] = '</div></div>';

        return $form;
    }


    /**
     * {@inheritdoc}
     */
    // Вместо hook_form_validate.
    function validateForm(array &$form, FormStateInterface $form_state){
        $clicked_button = end($form_state
            ->getTriggeringElement()['#parents']);
        if ($clicked_button != 'remove_button') {
            $main_texture = $form_state->getValue('main_texture');
			if($main_texture != NULL){
				$oNewFile = \Drupal\file\Entity\File::load(reset($main_texture));
				$fileUrl = $oNewFile->getFileUri();
				$image_factory = \Drupal::service('image.factory');
				$image = $image_factory->get($fileUrl);
				$regex = '/\\.(' . preg_replace('/ +/', '|', preg_quote('png jpg jpeg')) . ')$/i';
				if (!preg_match($regex, $fileUrl)) {
					$form_state->setErrorByName('formfile', $this-> t('Only files with the following extensions are allowed: png, jpg, jpeg'));
				}
			}
            $tiles = $form_state->getValue('tiles');
			if($tiles != NULL){
				$oNewFile = \Drupal\file\Entity\File::load(reset($tiles));
				$fileUrl = $oNewFile->getFileUri();
				$image_factory = \Drupal::service('image.factory');
				$image = $image_factory->get($fileUrl);
				$regex = '/\\.(' . preg_replace('/ +/', '|', preg_quote('png jpg jpeg')) . ')$/i';
				if (!preg_match($regex, $fileUrl)) {
					$form_state->setErrorByName('formfile', $this-> t('Only files with the following extensions are allowed: png, jpg, jpeg'));
				}
			}
        }
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        $id = $form_state->getValue('id');
		
        $method = 'Collections';


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
		$tiles = $form_state->getValue('tiles');
		if($tiles != NULL){
			$oNewFile = \Drupal\file\Entity\File::load(reset($tiles));
			$fileUrl = $oNewFile->getFileUri();
			$absolute_path = \Drupal::service('file_system')->realpath($fileUrl);
			$ext = pathinfo($absolute_path, PATHINFO_EXTENSION);
			$data[] = [
					'name'     => 'tiles',
					'contents' => fopen($absolute_path, 'r'),
				];
		}
			
		if($data != []){
			$result = SofaApiLogic::send($method, $data, 'POST_MULT', $id.'/images', 'array');
			if($result == []){
				drupal_set_message('Успешно');
				//drupal_set_message(['#type' => 'item', '#markup' => print_r($result, true),]);
			} else {
				drupal_set_message('Не обновлено', 'error');
			}
		}
    }

}