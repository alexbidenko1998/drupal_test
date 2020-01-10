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

class SofaAddImage extends FormBase {
    /**
     * {@inheritdoc}.
     */
    // Метод для котороый возвращает ид формы.
    public function getFormId() {
        return 'SofaAddImage_form';
    }

    /**
     * {@inheritdoc}.
     */
    // Вместо hook_form.
    public function buildForm(array $form, FormStateInterface $form_state, $sofaId = '') {

		
        $form['id'] = array(
            '#type' => 'hidden',
            '#default_value' => $sofaId,
        );

        $validators = array(
        );
		
        $form['formfile'] = array(
            '#type' => 'managed_file',
            '#name' => 'formfile',
            '#title' => 'Изображение (png, jpg, jpeg)',
            '#size' => 20,
            '#description' => t('PNG, JPG, JPEG format only'),
            '#upload_validators' => $validators,
            '#upload_location' => 'public://image_file/',
            '#required' => TRUE,
        );

        $form['formfile']['#limit_validation_errors'] = array();

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
            $formfile = $form_state->getValue('formfile');
            $oNewFile = \Drupal\file\Entity\File::load(reset($formfile));
            $fileUrl = $oNewFile->getFileUri();
            $image_factory = \Drupal::service('image.factory');
            $image = $image_factory->get($fileUrl);
            /*if ($image->isValid()) {
                $data['formfile'] = [];
                //drupal_set_message(['#type' => 'item', '#markup' => print_r(, true),]);
                // Check that it is smaller or equal to the given dimensions.
                list($width, $height) = explode('x', '1242x207');
                if ($image->getWidth() != $width) {
                    $form_state->setErrorByName('formfile', $this->t('Please upload an image that is exactly 1242х207 pixels.'));
                } else if ($image->getHeight() != $height) {
                    $form_state->setErrorByName('formfile', $this->t('Please upload an image that is exactly 1242х207 pixels.'));
                }
            } else {
                $form_state->setErrorByName('formfile', $this->t('File is not a valid image.'));
            }*/

            $regex = '/\\.(' . preg_replace('/ +/', '|', preg_quote('png jpg jpeg')) . ')$/i';
            if (!preg_match($regex, $fileUrl)) {
                $form_state->setErrorByName('formfile', $this-> t('Only files with the following extensions are allowed: png, jpg, jpeg'));
            }
        }
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        $id = $form_state->getValue('id');
		
        $formfile = $form_state->getValue('formfile');
        $method = 'Sofas';
		
		$oNewFile = \Drupal\file\Entity\File::load(reset($formfile));
		$fileUrl = $oNewFile->getFileUri();
		$absolute_path = \Drupal::service('file_system')->realpath($fileUrl);
		$ext = pathinfo($absolute_path, PATHINFO_EXTENSION);

		$data = [
			[
				'name'     => 'file',
				'contents' => fopen($absolute_path, 'r'),
			]
		];
		
        $result = SofaApiLogic::send($method, $data, 'POST_MULT', $id.'/image', 'array');
        if($result === []){
            drupal_set_message('Успешно');
            //drupal_set_message(['#type' => 'item', '#markup' => print_r($result, true),]);
        } else {
            drupal_set_message('Не обновлено', 'error');
        }
    }

}