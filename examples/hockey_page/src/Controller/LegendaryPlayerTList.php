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

class LegendaryPlayerTList extends ControllerBase
{
    public function content()
    {
        $empty = 'Список пуст';

        $itemCount = 20;
		
		
        $error = ['#type' => 'item', '#markup' => 'Error',];

        $method = 'getJsonDocument';
        $data = ['name' => 'shop'];
        $result = HockeyApiLogic::send($method, $data);

        $players = HockeyApiLogic::filter('category', 'player_t', 'Equals', $result);

        $page = $_GET['page'] ?? 0;
        if($page < 0 ){ $page=0; }
        $offset = $page * $itemCount;
		

        if ($players == NULL) {
            return [
                '#type' => 'item',
                '#markup' => $empty,
            ];
        }
		
        $header = array(
            'playerId' => array('data' => 'ID', 'field' => 'playerId'),
            'name' => array('data' => 'Имя', 'field' => 'name'),
            'surname' => array('data' => 'Фамилия', 'field' => 'surname'),
            'type' => array('data' => 'Тип', 'field' => 'type'),
            'category' => array('data' => 'Категория', 'field' => 'category'),
            'Активный',
            ' ',
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

        foreach ($players as $item){
            $id = $item['id'];
            $name =  $item['product'][0][0]['name'] ?? NULL;
            $surname =  $item['product'][0][0]['surname'] ?? NULL;
            $type =  $item['product'][0][0]['playerType'] ?? NULL;
            $category =  $item['product'][0][0]['category'] ?? NULL;

            if (( $item['product'][0][0]['active'] ?? NULL) != 'true') {
                $active = 'Нет';
            } else {
                $active = 'Да';
            }

            $playerUrl = '<a href="/legendaryplayert/'.$id.'/edit" class="btn btn-default col-xs-12 glyphicon glyphicon-list-alt">Редактировать</a>';

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
                'category' => [
                    '#type' => 'item',
                    '#markup' => $category,
                ],
                'active' => [
                    '#type' => 'item',
                    '#markup' => $active,
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

