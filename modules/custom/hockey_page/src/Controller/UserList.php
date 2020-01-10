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

class UserList extends ControllerBase
{
    public function content()
    {
        $empty = 'Не удалось получить данные';

        $itemCount = 20;

        $page = $_GET['page'] ?? 0;
        if($page < 0 ){ $page=0; }
        $offset = $page * $itemCount;

        if( isset($_POST['type']) ) {
            if($_POST['type'] == 'Unblock' && isset($_POST['id'])){
                $d = [
                    'accountId' => $_POST['id'],
                    'banned'    => false,
                ];
                $r = HockeyApiLogic::send('accountUpdate', $d);
                if($r === array()) {
                    HockeyApiLogic::my_goto("Разблокирован пользователь ID: {$_POST['id']}", $_GET);
                } else {
                    HockeyApiLogic::my_goto(['#type' => 'item', '#markup' => print_r($r, true) ], $_GET);
                }
            }
            if($_POST['type'] == 'Change' && isset($_POST['id']) && isset($_POST['teamName']) && isset($_POST['shortTeamName'])){
                $d = [
                    'accountId' => $_POST['id'],
                    'teamName'    => $_POST['teamName'],
                    'teamNameShort'    => $_POST['shortTeamName'],
                ];
                $r = HockeyApiLogic::send('accountChangeName', $d);
                if($r === array()) {
                    HockeyApiLogic::my_goto("Измененно имя пользователя ID: {$_POST['id']}", $_GET);
                } else {
                    HockeyApiLogic::my_goto(['#type' => 'item', '#markup' => print_r($r, true) ], $_GET);
                }
            }
            if($_POST['type'] == 'Block' && isset($_POST['id'])){
                $d = [
                    'accountId' => $_POST['id'],
                    'banned'    => true,
                ];
                $r = HockeyApiLogic::send('accountUpdate', $d);
                if($r === array()) {
                    HockeyApiLogic::my_goto("Заблокирован пользователь ID: {$_POST['id']}", $_GET);
                } else {
                    HockeyApiLogic::my_goto(['#type' => 'item', '#markup' => print_r($r, true) ], $_GET);
                }
            }
            if($_POST['type'] == 'Delete' && isset($_POST['id'])){
                $d = [
                    'accountId' => $_POST['id'],
                ];
                $r = HockeyApiLogic::send('accountDelete', $d);
                if($r === array()) {
                HockeyApiLogic::my_goto("Удален пользователь ID: {$_POST['id']}");
                } else {
                    HockeyApiLogic::my_goto(['#type' => 'item', '#markup' => print_r($r, true) ], $_GET);
                }
            }
        }
        $method = 'getAccounts';
        $data = ['offset' => $offset, 'count' => $itemCount];

        if(isset($_GET['order'])) {
            $order = null;
            if ($_GET['order'] == 'ID') {$order = 'account_id';}
            if ($_GET['order'] == 'Имя') {$order = 'account_name';}
            if ($_GET['order'] == 'Название команды') {$order = 'team_name';}
            if ($_GET['order'] == 'Краткое название') {$order = 'short_team_name';}
            if ($_GET['order'] == 'Уровень') {$order = 'level';}
            if ($_GET['order'] == 'Уровень команды') {$order = 'team_level';}
            if ($_GET['order'] == 'Лига') {$order = 'league';}
            if ($_GET['order'] == 'Ранг') {$order = 'league_rank';}
            if ($_GET['order'] == 'Бот') {$order = 'bot';}
            if ($_GET['order'] == 'В сети') {$order = 'online';}
            if ($_GET['order'] == 'Заблокирован') {$order = 'blocked';}
            if ($_GET['order'] == 'Создан') {$order = 'created';}

            if ($order !== null) {
                $sort = isset($_GET['sort']) ? ($_GET['sort'] === 'desc' ? 'desc' : 'asc') : 'asc';
                $data['sort']['orders'] = [];
                $data['sort']['orders'][] = ['field' => $order, 'dir' => $sort];
            }
        }

        if(isset($_GET['accountId'])      && $_GET['accountId']      != '' ){ $data['accountId']      = $_GET['accountId'];      }
        if(isset($_GET['teamName'])      && $_GET['teamName']      != '' ){ $data['teamName']      = $_GET['teamName'];      }
        if(isset($_GET['blocked'])  && $_GET['blocked']  != '' ){ $data['blocked']  = $_GET['blocked'];  }
        if(isset($_GET['fromLevel']) && $_GET['fromLevel'] != '' ){ $data['teamLevel']['from'] = $_GET['fromLevel']; }
        if(isset($_GET['toLevel'])   && $_GET['toLevel']   != '' ){ $data['teamLevel']['to']   = $_GET['toLevel'];   }
        if(isset($_GET['league'])   && $_GET['league']   != '' ){ $data['league']   = $_GET['league'];   }
        if(isset($_GET['online'])   && $_GET['online']   != '' ){ $data['online']   = $_GET['online'];   }
        if(isset($_GET['bot'])   && $_GET['bot']   != '' ){ $data['bot']   = $_GET['bot'];   }
        if(isset($_GET['rank'])   && $_GET['rank']   != '' ){ $data['rank']   = $_GET['rank'];   }
        if(isset($_GET['push'])      && $_GET['push']      != '' ){ $data['push']      = $_GET['push'];      }

        $result = HockeyApiLogic::send($method, $data);

        if ($result == NULL) {
            return [
                '#type' => 'item',
                '#markup' => $empty,
            ];
        }

        $header = array(
            'accountId' => array('data' => 'ID', 'field' => 'accountId'),
            'teamName' => array('data' => 'Название команды', 'field' => 'teamName'),
            'shortTeamName' => array('data' => 'Краткое название', 'field' => 'shortTeamName'),
            ' ',
            'level' => array('data' => 'Уровень', 'field' => 'level'),
            'teamLevel' => array('data' => 'Уровень команды', 'field' => 'teamLevel'),
            'league' => array('data' => 'Лига', 'field' => 'league'),
            'leagueRank' => array('data' => 'Ранг', 'field' => 'leagueRank'),
            'bot' => array('data' => 'Бот', 'field' => 'bot'),
            'Уведомления',
            'created' => array('data' => 'Создан', 'field' => 'created'),
            'online' => array('data' => 'В сети', 'field' => 'online'),
            'banned' => array('data' => 'Заблокирован', 'field' => 'banned'),
            ' ',
            ' ',
            ' ',
        );

        $form['form'] = [
            '#type' => 'form',
            '#method' => 'get',
        ];

        $form['form']['filter'] = [
            'accountId'      => ['#type'=>'textfield', '#name' => 'accountId'     , '#placeholder' => 'ID'     ,
                '#value' => $_GET['accountId'] ?? '',
                '#prefix'   => '<div class="col-xs-12 col-md-3">', '#suffix'   => ' </div>',],
            'teamName'      => ['#type'=>'textfield', '#name' => 'teamName'     , '#placeholder' => 'Имя команды'     ,
                '#value' => $_GET['teamName'] ?? '',
                '#prefix'   => '<div class="col-xs-12 col-md-3">', '#suffix'   => ' </div>',],
            'fromLevel'      => ['#type'=>'textfield', '#name' => 'fromLevel'     , '#placeholder' => 'С уровня'     ,
                '#value' => $_GET['fromLevel'] ?? '',
                '#attributes' => array(' type' => 'number',),
                '#prefix'   => '<div class="col-xs-12 col-md-3">', '#suffix'   => ' </div>',],
            'toLevel'      => ['#type'=>'textfield', '#name' => 'toLevel'     , '#placeholder' => 'По уровень'     ,
                '#value' => $_GET['toLevel'] ?? '',
                '#attributes' => array(' type' => 'number',),
                '#prefix'   => '<div class="col-xs-12 col-md-3">', '#suffix'   => ' </div>',],
            'online'  => ['#type'=>'select', '#name' => 'online' , '#placeholder' => 'online' ,
                '#value' => $_GET['online'] ?? '', '#default_value' => $_GET['online'] ?? '',
                '#prefix'   => '<div class="col-xs-12 col-md-3">', '#suffix'   => ' </div>',
                '#options' => [
                    '' => 'В сети / не в сети',
                    'true' => 'В сети',
                    'false' => 'Не в сети',
                ],
            ],
            'bot'  => ['#type'=>'select', '#name' => 'bot' , '#placeholder' => 'bot' ,
                '#value' => $_GET['bot'] ?? '', '#default_value' => $_GET['bot'] ?? '',
                '#prefix'   => '<div class="col-xs-12 col-md-3">', '#suffix'   => ' </div>',
                '#options' => [
                    '' => 'бот / не бот',
                    'true' => 'Бот',
                    'false' => 'Не Бот',
                ],
            ],
            'rank'  => ['#type'=>'select', '#name' => 'rank' , '#placeholder' => 'rank' ,
                '#value' => $_GET['rank'] ?? '', '#default_value' => $_GET['rank'] ?? '',
                '#attributes' => array(' type' => 'number',),
                '#prefix'   => '<div class="col-xs-12 col-md-3">', '#suffix'   => ' </div>',
            ],
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
            'push'  => ['#type'=>'select', '#name' => 'push' , '#placeholder' => 'Уведомления' ,
                '#value' => $_GET['push'] ?? '', '#default_value' => $_GET['push'] ?? '',
                '#prefix'   => '<div class="col-xs-12 col-md-3">', '#suffix'   => ' </div>',
                '#options' => [
                    '' => 'Уведомления вкл/откл',
                    'false' => 'Отключены',
                    'true' => 'Включены',
                ],
            ],
            'blocked'  => ['#type'=>'select', '#name' => 'blocked' , '#placeholder' => 'Статус' ,
                '#value' => $_GET['blocked'] ?? '', '#default_value' => $_GET['blocked'] ?? '',
                '#prefix'   => '<div class="col-xs-12 col-md-3">', '#suffix'   => ' </div>',
                '#options' => [
                    '' => 'Все (активные и заблокированные)',
                    'false' => 'Активный',
                    'true' => 'Заблокирован',
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
            $Id = $item['accountId'];
            $teamName =  $item['teamName'] ?? NULL;
            $shortTeamName =  $item['shortTeamName'] ?? NULL;
            $level =  $item['level'] ?? NULL;
            $teamLevel =  $item['teamLevel'] ?? NULL;
            $league =  $item['league'] ?? NULL;
            $leagueRank =  $item['leagueRank'] ?? NULL;
            $created =  "".$item['created'][2].".".$item['created'][1].".".$item['created'][0] ?? NULL;

            $playerChangeUrl = '<p><a class="btn btn-default use-ajax col-xs-12 glyphicon glyphicon-pencil" data-dialog-type="modal" href="/player/'.$item['accountId'].'/changeName"></a></p>';
            $playerUrl = '<a href="/user/'.$item['accountId'].'/info" class="btn btn-default col-xs-12 glyphicon glyphicon-list-alt"> Подробнее</a>';

            $blockedFalse = 'Нет';
            $blockedTrue = 'Да';
            $blockedUndefined = 'Неопределено';
            $blocked = json_encode($item['banned'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?? NULL;

            if (( $item['online'] ?? NULL) != 'true') {
                $online = 'Нет';
            } else {
                $online = 'Да';
            }

            if (( $item['push'] ?? NULL) != 'true') {
                $push = 'Нет';
            } else {
                $push = 'Да';
            }
            if (( $item['bot'] ?? NULL) != 'true') {
                $bot = 'Нет';
            } else {
                $bot = 'Да';
            }
            if($blocked == 'true'){
                $blockedAction = [
                    '#type' => 'form',
                    '#method' => 'post',
                    'type'       => [ '#type'=>'hidden', '#name'=> 'type', '#value' => 'Unblock', ],
                    'id'   => [ '#type'=>'hidden', '#name'=> 'id', '#value' => $Id ],
                    'action' => ['#type'=>'submit'   , '#value' => ' Разблокировать', '#attributes' => [
                        'onclick' => 'if(!confirm("Разблокировать пользователя?")){return false;}',
                        'class'=>['col-xs-12', 'btn-info', 'glyphicon glyphicon-log-out'], ], ],
                ];
                $blocked = $blockedTrue;
            } else {
                $blockedAction = [
                    '#type' => 'form',
                    '#method' => 'post',
                    'type'       => [ '#type'=>'hidden', '#name'=> 'type', '#value' => 'Block', ],
                    'id'   => [ '#type'=>'hidden', '#name'=> 'id', '#value' => $Id ],
                    'action' => ['#type'=>'submit'   , '#value' => ' Заблокировать', '#attributes' => [
                        'onclick' => 'if(!confirm("Заблокировать пользователя?")){return false;}',
                        'class'=>['col-xs-12', 'btn-warning', 'glyphicon glyphicon-lock'], ], ],
                ];
                $blocked = $blockedFalse;
            }

            $deleteAction = [
                '#type' => 'form',
                '#method' => 'post',
                'type'       => [ '#type'=>'hidden', '#name'=> 'type', '#value' => 'Delete', ],
                'id'   => [ '#type'=>'hidden', '#name'=> 'id', '#value' => $Id ],
                'action' => ['#type'=>'submit'   , '#value' => 'Удалить', '#attributes' => [
                    'onclick' => 'if(!confirm("Удалить пользователя?")){return false;}',
                    'class'=>['col-xs-12', 'btn-danger'], ], ],
            ];


            $form['table'][] = [
                'accountId' => [
                    '#type' => 'item',
                    '#markup' => $Id,
                ],
                'teamName' => [
                    '#type' => 'item',
                    '#markup' => $teamName,
                ],
                'shortTeamName' => [
                    '#type' => 'item',
                    '#markup' => $shortTeamName,
                ],
                'changeAction'  => [
                    '#type' => 'item',
                    '#markup' => $playerChangeUrl,
                ],
                'level' => [
                    '#type' => 'item',
                    '#markup' => $level,
                ],
                'teamLevel' => [
                    '#type' => 'item',
                    '#markup' => $teamLevel,
                ],
                'league' => [
                    '#type' => 'item',
                    '#markup' => $league,
                ],
                'leagueRank' => [
                    '#type' => 'item',
                    '#markup' => $leagueRank,
                ],
                'bot' => [
                    '#type' => 'item',
                    '#markup' => $bot,
                ],
                'push' => [
                    '#type' => 'item',
                    '#markup' => $push,
                ],
                'created' => [
                    '#type' => 'item',
                    '#markup' => $created,
                ],
                'online' => [
                    '#type' => 'item',
                    '#markup' => $online,
                ],
                'status' => [
                    '#type' => 'item',
                    '#markup' => $blocked,
                ],
                //'blockedAction' => $blockedAction,
                //'deleteAction' => $deleteAction,
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

