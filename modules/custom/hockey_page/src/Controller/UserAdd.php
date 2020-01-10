<?php
/**
 * Created by PhpStorm.
 * User: saint
 * Date: 05.03.2018
 * Time: 19:02
 */

namespace Drupal\hockey_page\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;

class UserAdd extends ControllerBase {
    /**
     * {@inheritdoc}.
     */
    // Вместо hook_form.
    public function content($userId = '') {

        $error = [ '#type' => 'item', '#markup' => 'Error',];
        $empty = 'Не удалось получить данные';

        if( isset($_POST['type']) ) {
            if($_POST['type'] == 'moneyAdd' && isset($_POST['count'], $_POST['resourse'])){
                $_POST['count'] = (int) $_POST['count'];
                $d = [
                    'accountId' => $userId,
                    'count'  => $_POST['count']

                ];
                $r = HockeyApiLogic::send('accountAddMoney', $d);
                if( $r === array()) {
                    HockeyApiLogic::my_goto("Добавлено {$_POST['name_resourse']} (Количество: {$_POST['count']})");
                } else {
                    HockeyApiLogic::my_goto(['#type' => 'item', '#markup' => print_r($r, true) ]);
                }
            }
            if($_POST['type'] == 'moneyDelete' && isset($_POST['count'], $_POST['resourse'])){
                $_POST['count'] = (int) $_POST['count'];
                $d = [
                    'accountId' => $userId,
                    'count'  => -1*$_POST['count']

                ];
                $r = HockeyApiLogic::send('accountAddMoney', $d);
                if($r === array()) {
                    HockeyApiLogic::my_goto("Удалено {$_POST['name_resourse']} (Количество: {$_POST['count']})");
                } else {
                    HockeyApiLogic::my_goto(['#type' => 'item', '#markup' => print_r($r, true) ]);
                }
            }
            if($_POST['type'] == 'tokenAdd' && isset($_POST['count'], $_POST['resourse'])){
                $_POST['count'] = (int) $_POST['count'];
                $d = [
                    'accountId' => $userId,
                    'count'  => $_POST['count']

                ];
                $r = HockeyApiLogic::send('accountAddToken', $d);
                if( $r === array()) {
                    HockeyApiLogic::my_goto("Добавлено {$_POST['name_resourse']} (Количество: {$_POST['count']})");
                } else {
                    HockeyApiLogic::my_goto(['#type' => 'item', '#markup' => print_r($r, true) ]);
                }
            }
            if($_POST['type'] == 'tokenDelete' && isset($_POST['count'], $_POST['resourse'])){
                $_POST['count'] = (int) $_POST['count'];
                $d = [
                    'accountId' => $userId,
                    'count'  => -1*$_POST['count']

                ];
                $r = HockeyApiLogic::send('accountAddToken', $d);
                if($r === array()) {
                    HockeyApiLogic::my_goto("Удалено {$_POST['name_resourse']} (Количество: {$_POST['count']})");
                } else {
                    HockeyApiLogic::my_goto(['#type' => 'item', '#markup' => print_r($r, true) ]);
                }
            }
            if($_POST['type'] == 'expAdd' && isset($_POST['count'], $_POST['resourse'])){
                $_POST['count'] = (int) $_POST['count'];
                $d = [
                    'accountId' => $userId,
                    'count'  => $_POST['count']

                ];
                $r = HockeyApiLogic::send('accountAddExp', $d);
                if( $r === array()) {
                    HockeyApiLogic::my_goto("Добавлено {$_POST['name_resourse']} (Количество: {$_POST['count']})");
                } else {
                    HockeyApiLogic::my_goto(['#type' => 'item', '#markup' => print_r($r, true) ]);
                }
            }
            if($_POST['type'] == 'expDelete' && isset($_POST['count'], $_POST['resourse'])){
                $_POST['count'] = (int) $_POST['count'];
                $d = [
                    'accountId' => $userId,
                    'count'  => -1*$_POST['count']

                ];
                $r = HockeyApiLogic::send('accountAddExp', $d);
                if($r === array()) {
                    HockeyApiLogic::my_goto("Удалено {$_POST['name_resourse']} (Количество: {$_POST['count']})");
                } else {
                    HockeyApiLogic::my_goto(['#type' => 'item', '#markup' => print_r($r, true) ]);
                }
            }
            if($_POST['type'] == 'league' && isset($_POST['count'])){
                $_POST['count'] = (int) $_POST['count'];
                $d = [
                    'accountId' => $userId,
                    'leagueRank'  => $_POST['count']

                ];
                $r = HockeyApiLogic::send('accountUpdate', $d);
                if($r === array()) {
                    HockeyApiLogic::my_goto("Ранг изменен: {$_POST['count']}");
                } else {
                    HockeyApiLogic::my_goto(['#type' => 'item', '#markup' => print_r($r, true) ]);
                }
            }
        }

        $method = 'getAccountInfo';
        $data = ['accountId' => $userId];
        $result = HockeyApiLogic::send($method, $data);

        $form['name'] = array(
            '#type' => 'hidden',
            '#default_value' => $userId,
        );

        $form['table'] = array(
            '#type' => 'table',
            '#header' => ['Ресурс', 'Количество'],
            '#empty' => $empty,
            '#prefix' => '<div class="col-xs-12"><div class="row">', '#suffix' => '</div></div>',
            '#weight' => 0,
        );

        $arr = [
            'money' => 'Деньги',
            'token' => 'Токены',
            'exp' => 'Опыт',
            ];


        $form['table'][] = [
            'resourse'    => [ '#type' => 'item', '#markup' => 'Деньги',],
            'count'     => [ '#type' => 'item', '#markup' => $result['money'],],
            'add'      => [
                '#type' => 'form',
                '#method' => 'post',
                'type'       => [ '#type'=>'hidden','#name'=> 'type', '#value' => 'moneyAdd', ],
                'resourse'     => [ '#type'=>'hidden','#name'=> 'resourse', '#value' => 'money' ],
                'name_resourse'     => [ '#type'=>'hidden','#name'=> 'name_resourse', '#value' => 'Деньги' ],
                'count'      => [
                    '#type'=>'textfield', '#name' => 'count', '#placeholder' => 'Количество',
                    '#prefix'   => '<div class="col-xs-6">', '#suffix'   => '</div>',
                ],
                'action' => ['#type'=>'submit'   , '#value' => 'Добавить', '#attributes' => ['class'=>['col-xs-6', 'btn-success'], ], ],
            ],
            'delete'      => [
                '#type' => 'form',
                '#method' => 'post',
                'type'       => [ '#type'=>'hidden', '#name'=> 'type', '#value' => 'moneyDelete', ],
                'resourse'   => [ '#type'=>'hidden', '#name'=> 'resourse', '#value' => 'money' ],
                'name_resourse'     => [ '#type'=>'hidden','#name'=> 'name_resourse', '#value' => 'Деньги' ],
                'count'      => [
                    '#type'=>'textfield', '#name' => 'count', '#placeholder' => 'Количество',
                    '#prefix'   => '<div class="col-xs-6">', '#suffix'   => '</div>',
                ],
                'action' => ['#type'=>'submit'   , '#value' => 'Удалить', '#attributes' => ['class'=>['col-xs-6', 'btn-danger'], ], ],
            ],
        ];

        $form['table'][] = [
            'resourse'    => [ '#type' => 'item', '#markup' => 'Токены',],
            'count'     => [ '#type' => 'item', '#markup' => $result['tokens'],],
            'add'      => [
                '#type' => 'form',
                '#method' => 'post',
                'type'       => [ '#type'=>'hidden','#name'=> 'type', '#value' => 'tokenAdd', ],
                'resourse'     => [ '#type'=>'hidden','#name'=> 'resourse', '#value' => 'token' ],
                'name_resourse'     => [ '#type'=>'hidden','#name'=> 'name_resourse', '#value' => 'Токены' ],
                'count'      => [
                    '#type'=>'textfield', '#name' => 'count', '#placeholder' => 'Количество',
                    '#prefix'   => '<div class="col-xs-6">', '#suffix'   => '</div>',
                ],
                'action' => ['#type'=>'submit'   , '#value' => 'Добавить', '#attributes' => ['class'=>['col-xs-6', 'btn-success'], ], ],
            ],
            'delete'      => [
                '#type' => 'form',
                '#method' => 'post',
                'type'       => [ '#type'=>'hidden', '#name'=> 'type', '#value' => 'tokenDelete', ],
                'resourse'   => [ '#type'=>'hidden', '#name'=> 'resourse', '#value' => 'token' ],
                'name_resourse'     => [ '#type'=>'hidden','#name'=> 'name_resourse', '#value' => 'Токены' ],
                'count'      => [
                    '#type'=>'textfield', '#name' => 'count', '#placeholder' => 'Количество',
                    '#prefix'   => '<div class="col-xs-6">', '#suffix'   => '</div>',
                ],
                'action' => ['#type'=>'submit'   , '#value' => 'Удалить', '#attributes' => ['class'=>['col-xs-6', 'btn-danger'], ], ],
            ],
        ];

        $form['table'][] = [
            'resourse'    => [ '#type' => 'item', '#markup' => 'Опыт',],
            'count'     => [ '#type' => 'item', '#markup' => $result['exp'],],
            'add'      => [
                '#type' => 'form',
                '#method' => 'post',
                'type'       => [ '#type'=>'hidden','#name'=> 'type', '#value' => 'expAdd', ],
                'resourse'     => [ '#type'=>'hidden','#name'=> 'resourse', '#value' => 'exp' ],
                'name_resourse'     => [ '#type'=>'hidden','#name'=> 'name_resourse', '#value' => 'Опыт' ],
                'count'      => [
                    '#type'=>'textfield', '#name' => 'count', '#placeholder' => 'Количество',
                    '#prefix'   => '<div class="col-xs-6">', '#suffix'   => '</div>',
                ],
                'action' => ['#type'=>'submit'   , '#value' => 'Добавить', '#attributes' => ['class'=>['col-xs-6', 'btn-success'], ], ],
            ],
            'delete'      => [
                '#type' => 'form',
                '#method' => 'post',
                'type'       => [ '#type'=>'hidden', '#name'=> 'type', '#value' => 'expDelete', ],
                'resourse'   => [ '#type'=>'hidden', '#name'=> 'resourse', '#value' => 'exp' ],
                'name_resourse'     => [ '#type'=>'hidden','#name'=> 'name_resourse', '#value' => 'Опыт' ],
                'count'      => [
                    '#type'=>'textfield', '#name' => 'count', '#placeholder' => 'Количество',
                    '#prefix'   => '<div class="col-xs-6">', '#suffix'   => '</div>',
                ],
                'action' => ['#type'=>'submit'   , '#value' => 'Удалить', '#attributes' => ['class'=>['col-xs-6', 'btn-danger'], ], ],
            ],
        ];

        $form['table'][] = [
            'resourse'    => [ '#type' => 'item', '#markup' => 'Ранг',],
            'count'     => [ '#type' => 'item', '#markup' => $result['leagueRank'],],
            'add'      => [
                '#type' => 'form',
                '#method' => 'post',
                'type'       => [ '#type'=>'hidden','#name'=> 'type', '#value' => 'league', ],
                'count'      => [
                    '#type'=>'textfield', '#name' => 'count', '#placeholder' => 'Значение',
                    '#prefix'   => '<div class="col-xs-6">', '#suffix'   => '</div>',
                ],
                'action' => ['#type'=>'submit'   , '#value' => 'Изменить', '#attributes' => ['class'=>['col-xs-6', 'btn-success'], ], ],
            ],
        ];

        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => t('Update'),
            '#weight' => 20,
        );

        return $form;
    }

}