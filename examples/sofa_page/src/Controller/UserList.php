<?php
/**
 * Created by PhpStorm.
 * User: saint
 * Date: 13.02.2018
 * Time: 11:30
 */

namespace Drupal\sofa_page\Controller;

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

        if( isset($_POST['type']) ) {
            /*if($_POST['type'] == 'Unblock' && isset($_POST['id'])){
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
            }*/
            if($_POST['type'] == 'Delete' && isset($_POST['login'])){
                $d = [];
                $r = SofaApiLogic::send('Users', $d, 'DELETE', $_POST['login']);
                if($r === []) {
					SofaApiLogic::my_goto("Удален пользователь: {$_POST['login']}");
                } else {
                    SofaApiLogic::my_goto(['#type' => 'item', '#markup' => print_r($r, true) ], $_GET);
                }
            }
        }
        $method = 'Users';
        $data = [];

        /*if(isset($_GET['order'])) {
            $order = null;
            if ($_GET['order'] == 'ID') {$order = 'account_id';}
            if ($_GET['order'] == 'Имя') {$order = 'account_name';}
            if ($_GET['order'] == 'Название команды') {$order = 'team_name';}

            if ($order !== null) {
                $sort = isset($_GET['sort']) ? ($_GET['sort'] === 'desc' ? 'desc' : 'asc') : 'asc';
                $data['sort']['orders'] = [];
                $data['sort']['orders'][] = ['field' => $order, 'dir' => $sort];
            }
        }

        if(isset($_GET['accountId'])      && $_GET['accountId']      != '' ){ $data['accountId']      = $_GET['accountId'];      }
        if(isset($_GET['teamName'])      && $_GET['teamName']      != '' ){ $data['teamName']      = $_GET['teamName'];      }
        if(isset($_GET['blocked'])  && $_GET['blocked']  != '' ){ $data['blocked']  = $_GET['blocked'];  }*/

        $result = SofaApiLogic::send($method, $data, 'GET', '?page='.$page.'&size='.$itemCount);

        if ($result == NULL) {
            return [
                '#type' => 'item',
                '#markup' => $empty,
            ];
        }
		
        $header = array(
            'login' => array('data' => 'Логин', 'field' => 'login'),
            'email' => array('data' => 'email', 'field' => 'email'),
            'permissions' => array('data' => 'permissions', 'field' => 'permissions'),
			'',
        );

        $form['form'] = [
            '#type' => 'form',
            '#method' => 'get',
        ];

        /*$form['form']['filter'] = [
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
            '#prefix'   => '<div class="row">',
            '#suffix'   => ' </div>',
        ];*/

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
		
        foreach ($result['values'] as $item){
            $login = $item['login'];
            $email =  $item['email'] ?? NULL;
            $permissions =  '';
			foreach ($item['roles'] as $role){
				if($permissions === '')
					$permissions = $role;
				else
					$permissions =  $permissions.', '.$role;
				
			}


            $deleteAction = [
                '#type' => 'form',
                '#method' => 'post',
                'type'       => [ '#type'=>'hidden', '#name'=> 'type', '#value' => 'Delete', ],
                'login'   => [ '#type'=>'hidden', '#name'=> 'login', '#value' => $login ],
                'action' => ['#type'=>'submit'   , '#value' => 'Удалить', '#attributes' => [
                    'onclick' => 'if(!confirm("Удалить пользователя?")){return false;}',
                    'class'=>['col-xs-12', 'btn-danger'], ], ],
            ];
			
			
            $form['table'][] = [
                'login' => [
                    '#type' => 'item',
                    '#markup' => $login,
                ],
                'email' => [
                    '#type' => 'item',
                    '#markup' => $email,
                ],
                'permissions'  => [
                    '#type' => 'item',
                    '#markup' => $permissions,
                ],
				'deleteAction' => $deleteAction,
                ];
        }

        $pageCount = $result['totalElements']/$itemCount;
        pager_default_initialize($pageCount, 1);

        $form['pager'] = [
            '#type' => 'pager',
            '#quantity' => 5,
        ];

        return $form;
    }
}

