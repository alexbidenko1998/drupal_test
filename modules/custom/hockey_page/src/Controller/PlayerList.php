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

class PlayerList extends ControllerBase
{
    public function content()
    {
        $empty = 'Не удалось получить данные';

        $itemCount = 20;

        $page = $_GET['page'] ?? 0;
        if($page < 0 ){ $page=0; }
        $offset = $page * $itemCount;

        $method = 'getPlayers';
        $data = ['offset' => $offset, 'count' => $itemCount];

        if(isset($_GET['order'])) {
            $order = null;
            if ($_GET['order'] == 'ID') {$order = 'account_id';}
            if ($_GET['order'] == 'Имя') {$order = 'player_name';}
            if ($_GET['order'] == 'Фамилия') {$order = 'player_surname';}
            if ($_GET['order'] == 'Национальность') {$order = 'nationality';}
            if ($_GET['order'] == 'Уровень') {$order = 'level';}
            if ($_GET['order'] == 'Тип') {$order = 'type';}
            if ($_GET['order'] == 'Легендарный') {$order = 'legendary';}

            if ($order !== null) {
                $sort = isset($_GET['sort']) ? ($_GET['sort'] === 'desc' ? 'desc' : 'asc') : 'asc';
                $data['sort']['orders'] = [];
                $data['sort']['orders'][] = ['field' => $order, 'dir' => $sort];
            }
        }


        if(isset($_GET['id'])      && $_GET['id']      != '' ){ $data['id']      = $_GET['id'];      }
        if(isset($_GET['name'])      && $_GET['name']      != '' ){ $data['name']      = $_GET['name'];      }
        if(isset($_GET['surname'])      && $_GET['surname']      != '' ){ $data['surname']      = $_GET['surname'];      }
        if(isset($_GET['type'])  && $_GET['type']  != '' ){ $data['type']  = $_GET['type'];  }
        if(isset($_GET['nationality'])   && $_GET['nationality']   != '' ){ $data['nationality']   = $_GET['nationality'];   }
        if(isset($_GET['fromLevel']) && $_GET['fromLevel'] != '' ){ $data['level']['from'] = $_GET['fromLevel']; }
        if(isset($_GET['toLevel'])   && $_GET['toLevel']   != '' ){ $data['level']['to']   = $_GET['toLevel'];   }

        $result = HockeyApiLogic::send($method, $data);

        if ($result == NULL) {
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
            'nationality' => array('data' => 'Национальность', 'field' => 'nationality'),
            'level' => array('data' => 'Уровень', 'field' => 'level'),
            'legendary' => array('data' => 'Легендарный', 'field' => 'legendary'),
            ' ',
        );

        $form['form'] = [
            '#type' => 'form',
            '#method' => 'get',
        ];

        $form['form']['filter'] = [
            'id'      => ['#type'=>'textfield', '#name' => 'id'     , '#placeholder' => 'ID'     ,
                '#value' => $_GET['id'] ?? '',
                '#prefix'   => '<div class="col-xs-12 col-md-3">', '#suffix'   => ' </div>',],
            'name'      => ['#type'=>'textfield', '#name' => 'name'     , '#placeholder' => 'Имя'     ,
                '#value' => $_GET['name'] ?? '',
                '#prefix'   => '<div class="col-xs-12 col-md-3">', '#suffix'   => ' </div>',],
            'surname'      => ['#type'=>'textfield', '#name' => 'surname'     , '#placeholder' => 'Фамилия'     ,
                '#value' => $_GET['surname'] ?? '',
                '#prefix'   => '<div class="col-xs-12 col-md-3">', '#suffix'   => ' </div>',],
            'fromLevel'      => ['#type'=>'textfield', '#name' => 'fromLevel'     , '#placeholder' => 'С уровня'     ,
                '#value' => $_GET['fromLevel'] ?? '',
                '#attributes' => array(' type' => 'number',),
                '#prefix'   => '<div class="col-xs-12 col-md-3">', '#suffix'   => ' </div>',],
            'toLevel'      => ['#type'=>'textfield', '#name' => 'toLevel'     , '#placeholder' => 'По уровень'     ,
                '#value' => $_GET['toLevel'] ?? '',
                '#attributes' => array(' type' => 'number',),
                '#prefix'   => '<div class="col-xs-12 col-md-3">', '#suffix'   => ' </div>',],
            'type'  => ['#type'=>'select', '#name' => 'type' , '#placeholder' => 'type' ,
                '#value' => $_GET['type'] ?? '', '#default_value' => $_GET['online'] ?? '',
                '#prefix'   => '<div class="col-xs-12 col-md-3">', '#suffix'   => ' </div>',
                '#options' => [
                    '' => 'Все',
                    'central_stricker' => 'Центральный нападающий',
                    'left_stricker' => 'Левый нападающий',
                    'right_stricker' => 'Правый нападающий',
                    'defender' => 'Защитник',
                    'goalkeeper' => 'Вратарь',
                ],
            ],
            'nationality'  => ['#type'=>'select', '#name' => 'nationality' , '#placeholder' => 'nationality' ,
                '#value' => $_GET['nationality'] ?? '', '#default_value' => $_GET['league'] ?? '',
                '#prefix'   => '<div class="col-xs-12 col-md-3">', '#suffix'   => ' </div>',
                '#options' => [
                    '' => 'Все национальности',
                    'russian' => 'russian',
                    'usa' => 'usa',
                    'canada' => 'canada',
                    'czech' => 'czech',
                    'finland' => 'finland',
                    'sweden' => 'sweden',
                    'none' => 'none',
                ],
            ],
            '#prefix'   => '<div class="row">',
            '#suffix'   => ' </div>',
        ];

        $form['form']['action']    = ['#type'=>'submit'   , '#value' => 'Фильтpaция'  , '#attributes' => ['class'=>['col-xs-12', 'btn-success', 'glyphicon glyphicon-filter']],
            '#prefix'   => '<div class="col-xs-12"><div class="row">', '#suffix'   => ' </div></div>',
        ];

        $form['table'] = array(
            '#type' => 'table',
            // '#caption' => $this->t('Sample Table'),
            '#header' => $header,
            '#empty' => $empty,
            '#prefix' => '<div class="col-xs-12"><div class="row">', '#suffix' => '</div></div>',
            '#weight' => 0,
        );

        foreach ($result['items'] as $item){
            $Id = $item['playerId'];
            $name =  $item['name'] ?? NULL;
            $surname =  $item['surname'] ?? NULL;
            $type =  $item['type'] ?? NULL;
            $level =  $item['level'] ?? NULL;
            $nationality =  $item['nationality'] ?? NULL;

            if (( $item['legendary'] ?? NULL) != 'true') {
                $legendary = 'Нет';
            } else {
                $legendary = 'Да';
            }

            $playerUrl = '<a href="/player/'.$item['playerId'].'/info" class="btn btn-default col-xs-12 glyphicon glyphicon-list-alt"> Подробнее</a>';

            $form['table'][] = [
                'accountId' => [
                    '#type' => 'item',
                    '#markup' => $Id,
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
                'nationality' => [
                    '#type' => 'item',
                    '#markup' => $nationality,
                ],
                'level' => [
                    '#type' => 'item',
                    '#markup' => $level,
                ],
                'legendary' => [
                    '#type' => 'item',
                    '#markup' => $legendary,
                ],
                'links' => [
                    '#type' => 'item',
                    '#markup' => $playerUrl,
                ],
                ];
        }

        $pageCount = $result['total']/$itemCount;
        pager_default_initialize($pageCount, 1);

        $form['pager'] = [
            '#type' => 'pager',
            '#quantity' => 5,
        ];

        return $form;
    }
}

