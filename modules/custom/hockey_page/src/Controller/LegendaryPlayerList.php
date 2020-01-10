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

class LegendaryPlayerList extends ControllerBase
{
    public function content()
    {
        $empty = 'Список пуст';

        $itemCount = 20;

        $page = $_GET['page'] ?? 0;
        if($page < 0 ){ $page=0; }
        $offset = $page * $itemCount;
		
		if(isset($_POST['type']) ) {
			$method = 'updateLegendaryPlayers';
            if($_POST['type'] == 'Others' && isset($_POST['id'])){
                $data = [
                    "week1" => [],
                    "week2" => [],
                    "week3" => [],
                    "others" => [$_POST['id']]];
                $r = HockeyApiLogic::send($method, $data);
                if($r != null) {
                    HockeyApiLogic::my_goto("Убран игрок ID: {$_POST['id']}");
                } else {
                    HockeyApiLogic::my_goto(['#type' => 'item', '#markup' => print_r($r, true) ], $_GET);
                }
            }
            if($_POST['type'] == 'Week1' && isset($_POST['id'])){
                $data = [
                    "week1" => [$_POST['id']],
                    "week2" => [],
                    "week3" => [],
                    "others" => []];
                $r = HockeyApiLogic::send($method, $data);
                if($r != null) {
                    HockeyApiLogic::my_goto("Текущая неделя ID: {$_POST['id']}");
                } else {
                    HockeyApiLogic::my_goto(['#type' => 'item', '#markup' => print_r($r, true) ], $_GET);
                }
            }
            if($_POST['type'] == 'Week2' && isset($_POST['id'])){
                $data = [
                    "week1" => [],
                    "week2" => [$_POST['id']],
                    "week3" => [],
                    "others" => []];
                $r = HockeyApiLogic::send($method, $data);
                if($r != null) {
                    HockeyApiLogic::my_goto("Слудующая неделя ID: {$_POST['id']}");
                } else {
                    HockeyApiLogic::my_goto(['#type' => 'item', '#markup' => print_r($r, true) ], $_GET);
                }
            }
            if($_POST['type'] == 'Week3' && isset($_POST['id'])){
                $data = [
                    "week1" => [],
                    "week2" => [],
                    "week3" => [$_POST['id']],
                    "others" => []];
                $r = HockeyApiLogic::send($method, $data);
                if($r != null) {
                    HockeyApiLogic::my_goto("Третья неделя ID: {$_POST['id']}");
                } else {
                    HockeyApiLogic::my_goto(['#type' => 'item', '#markup' => print_r($r, true) ], $_GET);
                }
            }
        }

        $method = 'getLegendaryPlayers';
        $data = ['offset' => $offset, 'count' => $itemCount];

        if(isset($_GET['order'])) {
            $order = null;
            if ($_GET['order'] == 'ID') {$order = 'player_id';}
            if ($_GET['order'] == 'Имя') {$order = 'player_name';}
            if ($_GET['order'] == 'Фамилия') {$order = 'player_surname';}
            if ($_GET['order'] == 'Категория') {$order = 'category';}
            if ($_GET['order'] == 'Тип') {$order = 'type';}

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
        if(isset($_GET['category'])   && $_GET['category']   != '' ){ $data['category']   = $_GET['category'];   }

        $result = HockeyApiLogic::send($method, $data);
		
        if ($result == NULL) {
            return [
                '#type' => 'item',
                '#markup' => $empty,
            ];
        }

        /*$form['week1'] = [
            '#type' => 'fieldset',
            '#title' => 'Текущая неделя',
        ];
		
        $header = array(
            'ID',
            'Имя',
            'Фамилия',
            'Тип',
            'Категория',
            ' ',
            ' ',
        );

        $form['week1']['form'] = [
            '#type' => 'form',
            '#method' => 'get',
        ];

        $form['week1']['table'] = array(
            '#type' => 'table',
            // '#caption' => $this->t('Sample Table'),
            '#header' => $header,
            '#empty' => $empty,
            '#prefix' => '<div class="col-xs-12"><div class="row">', '#suffix' => '</div></div>',
            '#weight' => 0,
        );

        foreach ($result['week1'] as $item){
            $id = $item['id'];
            $name =  $item['name'] ?? NULL;
            $surname =  $item['surname'] ?? NULL;
            $type =  $item['playerType'] ?? NULL;
            $category =  $item['category'] ?? NULL;


            $others = [
                '#type' => 'form',
                '#method' => 'post',
                'type'       => [ '#type'=>'hidden', '#name'=> 'type', '#value' => 'Others', ],
                'id'   => [ '#type'=>'hidden', '#name'=> 'id', '#value' => $id ],
                'action' => ['#type'=>'submit'   , '#value' => 'Убрать', '#attributes' => [
                    'onclick' => 'if(!confirm("Убрать игрока?")){return false;}',
                    'class'=>['col-xs-12', 'btn-danger'], ], ],
            ];

            $playerUrl = '<a href="/legendaryplayer/'.$id.'/edit" class="btn btn-default col-xs-12 glyphicon glyphicon-list-alt">Редактировать</a>';

            $form['week1']['table'][] = [
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
                'nationality' => [
                    '#type' => 'item',
                    '#markup' => $category,
                ],
				$others,
                'edit' => [
                    '#type' => 'item',
                    '#markup' => $playerUrl,
                ],
                ];
        }
		
        $form['week2'] = [
            '#type' => 'fieldset',
            '#title' => 'Следующая неделя',
        ];

        $form['week2']['form'] = [
            '#type' => 'form',
            '#method' => 'get',
        ];

        $form['week2']['table'] = array(
            '#type' => 'table',
            // '#caption' => $this->t('Sample Table'),
            '#header' => $header,
            '#empty' => $empty,
            '#prefix' => '<div class="col-xs-12"><div class="row">', '#suffix' => '</div></div>',
            '#weight' => 0,
        );

        foreach ($result['week2'] as $item){
            $id = $item['id'];
            $name =  $item['name'] ?? NULL;
            $surname =  $item['surname'] ?? NULL;
            $type =  $item['playerType'] ?? NULL;
            $category =  $item['category'] ?? NULL;

            $others = [
                '#type' => 'form',
                '#method' => 'post',
                'type'       => [ '#type'=>'hidden', '#name'=> 'type', '#value' => 'Others', ],
                'id'   => [ '#type'=>'hidden', '#name'=> 'id', '#value' => $id ],
                'action' => ['#type'=>'submit'   , '#value' => 'Убрать', '#attributes' => [
                    'onclick' => 'if(!confirm("Убрать игрока?")){return false;}',
                    'class'=>['col-xs-12', 'btn-danger'], ], ],
            ];

            $playerUrl = '<a href="/legendaryplayer/'.$id.'/edit" class="btn btn-default col-xs-12 glyphicon glyphicon-list-alt">Редактировать</a>';

            $form['week2']['table'][] = [
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
                'nationality' => [
                    '#type' => 'item',
                    '#markup' => $category,
                ],
                $others,
                'edit' => [
                    '#type' => 'item',
                    '#markup' => $playerUrl,
                ],
                ];
        }
		
        $form['week3'] = [
            '#type' => 'fieldset',
            '#title' => 'Третья неделя',
        ];

        $form['week3']['form'] = [
            '#type' => 'form',
            '#method' => 'get',
        ];

        $form['week3']['table'] = array(
            '#type' => 'table',
            // '#caption' => $this->t('Sample Table'),
            '#header' => $header,
            '#empty' => $empty,
            '#prefix' => '<div class="col-xs-12"><div class="row">', '#suffix' => '</div></div>',
            '#weight' => 0,
        );

        foreach ($result['week3'] as $item){
            $id = $item['id'];
            $name =  $item['name'] ?? NULL;
            $surname =  $item['surname'] ?? NULL;
            $type =  $item['playerType'] ?? NULL;
            $category =  $item['category'] ?? NULL;

            $others = [
                '#type' => 'form',
                '#method' => 'post',
                'type'       => [ '#type'=>'hidden', '#name'=> 'type', '#value' => 'Others', ],
                'id'   => [ '#type'=>'hidden', '#name'=> 'id', '#value' => $id ],
                'action' => ['#type'=>'submit'   , '#value' => 'Убрать', '#attributes' => [
                    'onclick' => 'if(!confirm("Убрать игрока?")){return false;}',
                    'class'=>['col-xs-12', 'btn-danger'], ], ],
            ];

            $playerUrl = '<a href="/legendaryplayer/'.$id.'/edit" class="btn btn-default col-xs-12 glyphicon glyphicon-list-alt">Редактировать</a>';

            $form['week3']['table'][] = [
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
                'nationality' => [
                    '#type' => 'item',
                    '#markup' => $category,
                ],
                $others,
                'edit' => [
                    '#type' => 'item',
                    '#markup' => $playerUrl,
                ],
                ];
        }
		
		
        $form['others'] = [
            '#type' => 'fieldset',
            '#title' => 'Список свободных игроков',
        ];*/
		
        $header = array(
            'playerId' => array('data' => 'ID', 'field' => 'playerId'),
            'name' => array('data' => 'Имя', 'field' => 'name'),
            'surname' => array('data' => 'Фамилия', 'field' => 'surname'),
            'type' => array('data' => 'Тип', 'field' => 'type'),
            'category' => array('data' => 'Категория', 'field' => 'category'),
            'Активный',
            /*' ',
            ' ',
            ' ',*/
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
            'category'  => ['#type'=>'select', '#name' => 'category' , '#placeholder' => 'category' ,
                '#value' => $_GET['category'] ?? '', '#default_value' => $_GET['league'] ?? '',
                '#prefix'   => '<div class="col-xs-12 col-md-3">', '#suffix'   => ' </div>',
                '#options' => [
                    '' => 'Все категории',
                    'r1' => 'r1',
                    'r2' => 'r2',
                    'r3' => 'r3',
                    'r4' => 'r4',
                    'r5' => 'r5',
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
		
        foreach ($result['others'] as $item){
            $id = $item['id'];
            $name =  $item['name'] ?? NULL;
            $surname =  $item['surname'] ?? NULL;
            $type =  $item['playerType'] ?? NULL;
            $category =  $item['category'] ?? NULL;

            if (( $item['legendary'] ?? NULL) != 'true') {
                $legendary = 'Нет';
            } else {
                $legendary = 'Да';
            }

            if (( $item['active'] ?? NULL) != 'true') {
                $active = 'Нет';
            } else {
                $active = 'Да';
            }

            /*$week1 = [
                '#type' => 'form',
                '#method' => 'post',
                'type'       => [ '#type'=>'hidden', '#name'=> 'type', '#value' => 'Week1', ],
                'id'   => [ '#type'=>'hidden', '#name'=> 'id', '#value' => $id ],
                'action' => ['#type'=>'submit'   , '#value' => 'Неделя 1', '#attributes' => [
                    'onclick' => 'if(!confirm("Выбрать игрока?")){return false;}',
                    'class'=>['col-xs-12', 'btn-primary'], ], ],
            ];

            $week2 = [
                '#type' => 'form',
                '#method' => 'post',
                'type'       => [ '#type'=>'hidden', '#name'=> 'type', '#value' => 'Week2', ],
                'id'   => [ '#type'=>'hidden', '#name'=> 'id', '#value' => $id ],
                'action' => ['#type'=>'submit'   , '#value' => 'Неделя 2', '#attributes' => [
                    'onclick' => 'if(!confirm("Выбрать игрока?")){return false;}',
                    'class'=>['col-xs-12', 'btn-primary'], ], ],
            ];

            $week3 = [
                '#type' => 'form',
                '#method' => 'post',
                'type'       => [ '#type'=>'hidden', '#name'=> 'type', '#value' => 'Week3', ],
                'id'   => [ '#type'=>'hidden', '#name'=> 'id', '#value' => $id ],
                'action' => ['#type'=>'submit'   , '#value' => 'Неделя 3', '#attributes' => [
                    'onclick' => 'if(!confirm("Выбрать игрока?")){return false;}',
                    'class'=>['col-xs-12', 'btn-primary'], ], ],
            ];*/

            $playerUrl = '<a href="/legendaryplayerr/'.$id.'/edit" class="btn btn-default col-xs-12 glyphicon glyphicon-list-alt">Редактировать</a>';

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
				/*$week1,
                $week2,
                $week3,*/
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

