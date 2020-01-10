<?php
/**
 * Created by PhpStorm.
 * User: saint
 * Date: 22.02.2018
 * Time: 11:40
 */

namespace Drupal\sofa_page\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;

class FabricInfo extends ControllerBase {
    // Название переменной такоже как в роуте!!!
    public function content($fabricId = '')
    {
        $empty = 'Не удалось получить данные';

       
		$method  = 'Fabrics';
		$result = SofaApiLogic::send($method, [], 'GET', $fabricId);

        if ($result == NULL) {
            return [
                '#type' => 'item',
                '#markup' => $empty,
            ];
        }


        $form = $this->renderArray($result,true);

        return $form;
    }

    public function renderArray($array, $start){
        $table = [];
        if($start){
            $table['table'] = array(
                '#type' => 'table',
                '#header' => ['Stat','Value',],
                '#empty' => 'Отсутствуют данные для отображения',
                '#prefix' => '<div class="col-xs-12"><div class="row">', '#suffix' => '</div></div>',
                '#weight' => 0,
            );
        } else {
            $table['table'] = array(
                '#type' => 'table',
                '#empty' => 'Отсутствуют данные для отображения',
                '#prefix' => '<div class="col-xs-12"><div class="row">', '#suffix' => '</div></div>',
            );
        }


        $keys = array_keys($array);
        foreach ($keys as $key) {
            $type = gettype($array[$key]);
            switch ($type) {
                case 'integer':
                case 'double':
                case 'float':
                case 'string':
                case 'object':
                    $value = $this->renderString($array[$key]);
                    break;
                case 'boolean':
                case 'NULL':
                    $value = $this->renderBool($array[$key]);
                    break;
                case 'array':
                    $value = $this->renderArray($array[$key],false);
                    break;
                default:
                    $value = $this->renderUndefened();
                    break;
            }
            if(gettype($key) === 'string')
                $table['table'][] = [
                    [
                        '#type' => 'item',
                        '#markup' => $key,
                    ],
                    $value,
                ];
            else
                $table['table'][] = [$value,];

        }

        return $table;
    }

    private function renderString($item){
		$regex = '/\\.(' . preg_replace('/ +/', '|', preg_quote('png jpg jpeg')) . ')$/i';
		if (preg_match($regex, $item)) {
			$uri = SofaApiLogic::getImageUrl().$item;
			return ['#type' => 'item', '#markup' => "<img src=\"{$uri}\"  width=\"25%\" height=\"25%\">"];
		}
        return ['#type' => 'item', '#markup' => $item,];
    }

    private function renderBool($item){
        return ['#type' => 'item', '#markup' => $item === true ? 'TRUE' : $item === false ? 'FALSE' : 'NULL',];
    }

    private function renderUndefened(){
        return ['#type' => 'item', '#markup' => 'Undefened',];
    }


}