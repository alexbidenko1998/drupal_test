<?php
/**
 * Created by PhpStorm.
 * User: saint
 * Date: 13.02.2018
 * Time: 15:20
 */

namespace Drupal\hockey_page\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
//use Drupal\Component\Serialization\Yaml;

class SettingsJsonInfo extends ControllerBase {
    // Название переменной такоже как в роуте!!!
    public function content($settingName)
    {
        $error = [ '#type' => 'item', '#markup' => 'Error',];

        $method = 'getJsonDocument';
        $data = ['name' => $settingName];
        $result = HockeyApiLogic::send($method, $data);

        if(empty($result)){
            drupal_set_message(['#type' => 'item', '#markup' => print_r($result, true),]);
            return $error;
        }


        return [
            '#type' => 'item',
            '#markup' => '<div class="json-renderer">'.json_encode($result, JSON_PRETTY_PRINT).'</div>',
            //'#markup' => '<div class="yaml-renderer">'.Yaml::dump($ymldata).'</div>',
            '#attached' => array(
                'library' => array(
                    'hockey_page/settingJsonInfo'
                ),
            ),
        ];
        //
        //YAML::encode($invoice)
    }

}