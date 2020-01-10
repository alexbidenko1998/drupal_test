<?php
/**
 * Created by PhpStorm.
 * User: saint
 * Date: 13.02.2018
 * Time: 11:30
 */

namespace Drupal\hockey_page\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Core\Database\Database;

class LegendaryTrainerList extends ControllerBase
{
    public function content()
    {
        $empty = 'Список пуст';

        $itemCount = 20;
		
		
        $error = ['#type' => 'item', '#markup' => 'Error',];

        $method = 'getJsonDocument';
        $data = ['name' => 'shop'];
        $result = HockeyApiLogic::send($method, $data);

        $trainers = HockeyApiLogic::filter('category', 'trainer', 'Equals', $result);

        $page = $_GET['page'] ?? 0;
        if($page < 0 ){ $page=0; }
        $offset = $page * $itemCount;
		

        if ($trainers == NULL) {
            return [
                '#type' => 'item',
                '#markup' => $empty,
            ];
        }
		
        $header = array(
            'ID',
            'Имя',
            'Фамилия',
            'Тип',
            'Уровень',
            'Бонус - Атака',
            'Бонус - Защита',
            'Бонус - Атака на своем поле',
            'Бонус - Защита на поле противника',
            'Бонус - Большинство',
            'Бонус - Меньшиство',
        );
	
        $form['form'] = [
            '#type' => 'form',
            '#method' => 'get',
        ];

        $form['table'] = array(
            '#type' => 'table',
            // '#caption' => $this->t('Sample Table'),
            '#header' => $header,
            '#empty' => $empty,
            '#prefix' => '<div class="col-xs-12"><div class="row">', '#suffix' => '</div></div>',
            '#weight' => 0,
        );

        foreach ($trainers as $item){
            $id = $item['id'];
            $name =  $item['product'][0][0]['name'] ?? NULL;
            $surname =  $item['product'][0][0]['surname'] ?? NULL;
            $type =  $item['product'][0][0]['trainerType'] ?? NULL;
            $level =  $item['product'][0][0]['level'] ?? NULL;
            $bonus1 =  $item['product'][0][0]['trainerBonus']['attack'] ?? NULL;
            $bonus2 =  $item['product'][0][0]['trainerBonus']['defend'] ?? NULL;
            $bonus3 =  $item['product'][0][0]['trainerBonus']['attackOnEnemySide'] ?? NULL;
            $bonus4 =  $item['product'][0][0]['trainerBonus']['defendOnAllySide'] ?? NULL;
            $bonus5 =  $item['product'][0][0]['trainerBonus']['more'] ?? NULL;
            $bonus6 =  $item['product'][0][0]['trainerBonus']['less'] ?? NULL;

            $playerUrl = '<a href="/legendarytrainer/'.$id.'/edit" class="btn btn-default col-xs-12 glyphicon glyphicon-list-alt">Редактировать</a>';

            $form['table'][] = [
                'accountId' => [
                    '#type' => 'item',
                    '#markup' => $id,
                ],
                'name' => [
                    '#type' => 'item',
                    '#markup' => $name,
                ],
                'surname' => [
                    '#type' => 'item',
                    '#markup' => $surname,
                ],
                'type' => [
                    '#type' => 'item',
                    '#markup' => $type,
                ],
                'level' => [
                    '#type' => 'item',
                    '#markup' => $level,
                ],
                'bonus1' => [
                    '#type' => 'item',
                    '#markup' => $bonus1,
                ],
                'bonus2' => [
                    '#type' => 'item',
                    '#markup' => $bonus2,
                ],
                'bonus3' => [
                    '#type' => 'item',
                    '#markup' => $bonus3,
                ],
                'bonus4' => [
                    '#type' => 'item',
                    '#markup' => $bonus4,
                ],
                'bonus5' => [
                    '#type' => 'item',
                    '#markup' => $bonus5,
                ],
                'bonus6' => [
                    '#type' => 'item',
                    '#markup' => $bonus6,
                ],
                'edit' => [
                    '#type' => 'item',
                    '#markup' => $playerUrl,
                ],
                ];
        }

        return $form;
    }
}

