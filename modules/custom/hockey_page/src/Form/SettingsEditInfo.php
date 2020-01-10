<?php
/**
 * Created by PhpStorm.
 * User: saint
 * Date: 13.02.2018
 * Time: 15:11
 */
namespace Drupal\hockey_page\Form;

use Drupal\hockey_page\Controller\HockeyApiLogic;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
//use Drupal\Component\Serialization\Yaml;
use Symfony\Component\Yaml\Yaml;
// use Drupal\Component\Utility\UrlHelper;
use Exception;


class SettingsEditInfo extends FormBase  {

    /**
     * {@inheritdoc}.
     */
    // РњРµС‚РѕРґ РґР»СЏ РєРѕС‚РѕСЂРѕС‹Р№ РІРѕР·РІСЂР°С‰Р°РµС‚ РёРґ С„РѕСЂРјС‹.
    public function getFormId() {
        return 'SettingsEditInfo_form';
    }

    /**
     * {@inheritdoc}.
     */
    // Р’РјРµСЃС‚Рѕ hook_form.
    public function buildForm(array $form, FormStateInterface $form_state, $settingName = '') {

        $error = [ '#type' => 'item', '#markup' => 'Error',];

        $method = 'getJsonDocument';
        $data = ['name' => $settingName];
        $result = HockeyApiLogic::send($method, $data);

        //$result = Yaml::parse($result);


        $form['setting'] = array(
            '#type' => 'text_format',
            '#title' => 'JSON',
            '#required' => TRUE,
            '#default_value'=> json_encode($result, JSON_PRETTY_PRINT),
            '#format' => 'json',
            '#base_type' => 'textarea',
        );

        $form['name'] = array(
            '#type' => 'hidden',
            '#default_value' => $settingName,
        );



        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => '  РР·РјРµРЅРёС‚СЊ',
            '#attributes' => [
                'class' => ['col-xs-12', 'btn-info', 'glyphicon glyphicon-wrench']
            ]
        );

        $form['#prefix'] = '<div class="row"><div class="col-xs-12">';
        $form['#suffix'] = '</div></div>';

        return $form;

    }

    /**
     * {@inheritdoc}
     */
    // Р’РјРµСЃС‚Рѕ hook_form_validate.
    public function validateForm(array &$form, FormStateInterface $form_state) {
    }



    /**
     * {@inheritdoc}
     */
    // Р’РјРµСЃС‚Рѕ hook_form_submit.
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $method  = 'setJsonDocument';
        $setting = $form_state->getValue('setting');
        $setting = json_decode($setting['value']);
        $name    = $form_state->getValue('name');
        $data    = ['name' => $name, 'json' => $setting];
        if($setting != ''){
            $result = HockeyApiLogic::send($method, $data);
            if($result === array()){
                drupal_set_message('Успешно');
                //drupal_set_message(['#type' => 'item', '#markup' => print_r($result, true),]);
            } else {
                drupal_set_message('Не обновлено', 'error');
            }
        }
        else
            drupal_set_message('Не может быть обновлено', 'error');
    }
}