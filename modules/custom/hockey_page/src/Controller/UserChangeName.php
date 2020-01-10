<?php
/**
 * Created by PhpStorm.
 * User: saint
 * Date: 03.04.2018
 * Time: 13:32
 */

namespace Drupal\hockey_page\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Core\Database\Database;
use Drupal\Component\Serialization\Json;

class UserChangeName
{
    public function content($playerId)
    {
        $form['playerId'] = array(
            '#type' => 'hidden',
            '#default_value' => $playerId,
        );

        $form['button'] = [
            '#type' => 'form',
            '#method' => 'post',
            'type'       => [ '#type'=>'hidden', '#name'=> 'type', '#value' => 'Change', ],
            'id'   => [ '#type'=>'hidden', '#name'=> 'id', '#value' => $playerId ],
            'teamName'      => [
                '#type'=>'textfield', '#name' => 'teamName', '#placeholder' => 'Новое имя команды',
            ],
            'shortTeamName'      => [
                '#type'=>'textfield', '#name' => 'shortTeamName', '#placeholder' => 'Новое короткое имя команды',
            ],
            'action' => ['#type'=>'submit'   , '#value' => '   Изменить', '#attributes' => [
                //'onclick' => 'if(!confirm("Заблокировать пользователя?")){return false;}',
                'class'=>['col-xs-12', 'btn-primary', 'glyphicon glyphicon-pencil'], ], ],
        ];


        $form['#prefix'] = '<div class="row"><div class="col-xs-12">';
        $form['#suffix'] = '</div></div>';

        return $form;
    }

}