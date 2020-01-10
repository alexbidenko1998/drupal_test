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

class TournamentList extends ControllerBase
{
    public function content()
    {
        $empty = 'Не удалось получить данные';

        $itemCount = 20;

        $page = $_GET['page'] ?? 0;
        if($page < 0 ){ $page=0; }
        $offset = $page * $itemCount;

        $method = 'getTournaments';
        $data = ['offset' => $offset, 'count' => $itemCount];

        if(isset($_GET['order'])) {
            $order = null;
            if ($_GET['order'] == 'ID') {$order = 'account_id';}
            if ($_GET['order'] == 'Тип') {$order = 'type';}
            if ($_GET['order'] == 'Активен') {$order = 'active';}
            if ($_GET['order'] == 'Минимальный уровень') {$order = 'minLevel';}
            if ($_GET['order'] == 'Максимальный уровень') {$order = 'maxLevel';}
            if ($_GET['order'] == 'Регистрация') {$order = 'registration';}
            if ($_GET['order'] == 'Лига') {$order = 'league';}
            if ($_GET['order'] == 'Ранг') {$order = 'rank';}
            if ($_GET['order'] == 'Кол-во ботов') {$order = 'bots';}

            if ($order !== null) {
                $sort = isset($_GET['sort']) ? ($_GET['sort'] === 'desc' ? 'desc' : 'asc') : 'asc';
                $data['sort']['orders'] = [];
                $data['sort']['orders'][] = ['field' => $order, 'dir' => $sort];
            }
        }

        if(isset($_GET['rank'])      && $_GET['rank']      != '' ){ $data['rank']      = $_GET['rank'];      }
        if(isset($_GET['type'])  && $_GET['type']  != '' ){ $data['type']  = $_GET['type'];  }
        if(isset($_GET['league'])   && $_GET['league']   != '' ){ $data['league']   = $_GET['league'];   }
        if(isset($_GET['active'])   && $_GET['active']   != '' ){ $data['active']   = $_GET['active'];   }

        $result = HockeyApiLogic::send($method, $data);

        if ($result == NULL) {
            return [
                '#type' => 'item',
                '#markup' => $empty,
            ];
        }

        $header = array(
            'tournamentId' => array('data' => 'ID', 'field' => 'tournamentId'),
            'type' => array('data' => 'Тип', 'field' => 'type'),
            'active' => array('data' => 'Активен', 'field' => 'active'),
            'league' => array('data' => 'Лига', 'field' => 'league'),
            'rank' => array('data' => 'Ранг', 'field' => 'rank'),
            'bots' => array('data' => 'Кол-во ботов', 'field' => 'bots'),
            'minLevel' => array('data' => 'Минимальный уровень', 'field' => 'minLevel'),
            'maxLevel' => array('data' => 'Максимальный уровень', 'field' => 'maxLevel'),
            'registration' => array('data' => 'Регистрация', 'field' => 'registration'),
            ' ',
        );

        $form['form'] = [
            '#type' => 'form',
            '#method' => 'get',
        ];

        $form['form']['filter'] = [
            'rank'      => ['#type'=>'textfield', '#name' => 'rank'     , '#placeholder' => 'Ранг'     ,
                '#value' => $_GET['rank'] ?? '',
                '#prefix'   => '<div class="col-xs-12 col-md-3">', '#suffix'   => ' </div>',],
            'league'  => ['#type'=>'select', '#name' => 'league' , '#placeholder' => 'Лига' ,
                '#value' => $_GET['league'] ?? '', '#default_value' => $_GET['league'] ?? '',
                '#prefix'   => '<div class="col-xs-12 col-md-3">', '#suffix'   => ' </div>',
                '#options' => [
                    '' => 'Все Лиги',
                    'european' => 'european',
                    'north_american' => 'north_american',
                    'czech' => 'czech',
                    'finnish' => 'finnish',
                    'swedish' => 'swedish',
                    'none' => 'none',
                ],
            ],
            'type'  => ['#type'=>'select', '#name' => 'type' , '#placeholder' => 'Тип турнира' ,
                '#value' => $_GET['type'] ?? '', '#default_value' => $_GET['type'] ?? '',
                '#prefix'   => '<div class="col-xs-12 col-md-3">', '#suffix'   => ' </div>',
                '#options' => [
                    '' => 'Все типы',
                    'month' => 'month',
                    'hour4' => 'hour4',
                    'hour12' => 'hour12',
                    'hour23' => 'hour23',
                ],
            ],
            'active'  => ['#type'=>'select', '#name' => 'active' , '#placeholder' => 'Активность' ,
                '#value' => $_GET['active'] ?? '', '#default_value' => $_GET['active'] ?? '',
                '#prefix'   => '<div class="col-xs-12 col-md-3">', '#suffix'   => ' </div>',
                '#options' => [
                    '' => 'Все типы',
                    'true' => 'Идет',
                    'false' => 'Завершен',
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
            $Id = $item['tournamentId'];
            $type =  $item['type'] ?? NULL;
            $league =  $item['league'] ?? NULL;
            $rank =  $item['rank'] ?? NULL;
            $bots =  $item['bots'] ?? NULL;
            $minLevel =  $item['minLevel'] ?? NULL;
            $maxLevel =  $item['maxLevel'] ?? NULL;

            if (( $item['registration'] ?? NULL) != 'true') {
                $registration = 'Нет';
            } else {
                $registration = 'Да';
            }
            if (( $item['active'] ?? NULL) != 'true') {
                $active = 'Завершен';
            } else {
                $active = 'Идет';
            }
            $playerUrl = '<a href="/tournament/'.$item['tournamentId'].'/info" class="btn btn-default col-xs-12 glyphicon glyphicon-list-alt"> Подробнее</a>';

            $form['table'][] = [
                'accountId' => [
                    '#type' => 'item',
                    '#markup' => $Id,
                ],
                'type' => [
                    '#type' => 'item',
                    '#markup' => $type,
                ],
                'active' => [
                    '#type' => 'item',
                    '#markup' => $active,
                ],
                'league' => [
                    '#type' => 'item',
                    '#markup' => $league,
                ],
                'rank' => [
                    '#type' => 'item',
                    '#markup' => $rank,
                ],
                'bots' => [
                    '#type' => 'item',
                    '#markup' => $bots,
                ],
                'minLevel' => [
                    '#type' => 'item',
                    '#markup' => $minLevel,
                ],
                'maxLevel' => [
                    '#type' => 'item',
                    '#markup' => $maxLevel,
                ],
                'registration' => [
                    '#type' => 'item',
                    '#markup' => $registration,
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

