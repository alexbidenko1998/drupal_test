<?php
/**
 * Created by PhpStorm.
 * User: saint
 * Date: 14.02.2018
 * Time: 14:51
 */

namespace Drupal\hockey_page\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;

class Statistic extends ControllerBase {
    // Название переменной такоже как в роуте!!!
    public function content($statisticName){

        $typeStatistic = ['day', 'month', 'year', 'full'];

        $tabs = '<ul class="nav nav-tabs nav-tab-statistic">
  <li class="active"><a data-toggle="tab" href="#day">По дням</a></li>
  <li><a data-toggle="tab" href="#month">По месецам</a></li>
  <li><a data-toggle="tab" href="#year">По годам</a></li>
  <li><a data-toggle="tab" href="#full">Весь период</a></li>
</ul>';

        $form = [
            '#prefix' => '<div class="row"><div class="col-xs-12">',
            '#suffix' => '</div></div>',
            'tabs' => [
                '#type' => 'item',
                '#markup' => $tabs,
            ],
            'tab-content' => [
                '#prefix' => '<div class="tab-content">',
                '#suffix' => '</div>',
            ],
        ];

        $formStatistic = [
            '#type' => 'item',
            '#markup' => '<div class="statistic" style="width: 100%; min-height: 400px;"></div>
<div class="pie" style="width: 100%; min-height: 400px;"></div>',
        ];
        $formDay = [
            '#type' => 'item',
            '#markup' => '
<form class="row" id="dayStatistic">
  <input type="hidden" id="type" value="'.$statisticName.'"/>
  <div class="col-xs-6">
    <input type="text" id="dayStart" class="form-control" value="" placeholder="19.07.2017" readonly required/> 
  </div>
  <div class="col-xs-6">
    <input type="text" id="dayEnd" class="form-control" value="" placeholder="29.07.2017" readonly required/> 
  </div>
  <div class="col-xs-12"><input type="submit" class="btn btn-block btn-success" value="Update"></div>
</form>',
            '#allowed_tags' => ['div','form','input','script','link'],

            '#attached' => array(
                'library' => array(
                    'crypto_page/statistic'
                ),
            ),
        ];
        $formMonth = [
            '#type' => 'item',
            '#markup' => '
<form class="row" id="monthStatistic">
  <input type="hidden" id="type" value="'.$statisticName.'"/>
  <div class="col-xs-6">
    <input type="text" id="monthStart" class="form-control" value="" placeholder="07.2017" readonly required/> 
  </div>
  <div class="col-xs-6">
    <input type="text" id="monthEnd" class="form-control" value="" placeholder="07.2017" readonly required/> 
  </div>
  <div class="col-xs-12"><input type="submit" class="btn btn-block btn-success" value="Update"></div>
</form>',
            '#allowed_tags' => ['div','form','input','script','link'],

            '#attached' => array(
                'library' => array(
                    'crypto_page/statistic'
                ),
            ),
        ];
        $formYear = [
            '#type' => 'item',
            '#markup' => '
<form class="row" id="yearStatistic">
  <input type="hidden" id="type" value="'.$statisticName.'"/>
  <div class="col-xs-6">
    <input type="text" id="yearStart" class="form-control" value="" placeholder="2016" readonly required/> 
  </div>
  <div class="col-xs-6">
    <input type="text" id="yearEnd" class="form-control" value="" placeholder="2017" readonly required/> 
  </div>
  <div class="col-xs-12"><input type="submit" class="btn btn-block btn-success" value="Update"></div>
</form>',
            '#allowed_tags' => ['div','form','input','script','link'],

        ];
        $formFull =  [
            '#type' => 'item',
            '#markup' => '
<form class="row" id="fullStatistic">
  <input type="hidden" id="type" value="'.$statisticName.'"/>
  <div class="col-xs-12"><input type="submit" class="btn btn-block btn-success" value="Update"></div>
</form>',
            '#allowed_tags' => ['div','form','input','script','link'],

        ];

        $form['tab-content']['day']   = [$formDay, $formStatistic];
        $form['tab-content']['month'] = [$formMonth, $formStatistic];
        $form['tab-content']['year']  = [$formYear, $formStatistic];
        $form['tab-content']['full']  = [$formFull, $formStatistic];

        $form['tab-content']['day']['#prefix']   = '<div id="day" class="tab-pane fade in active">';
        $form['tab-content']['day']['#suffix']   = '</div>';
        $form['tab-content']['month']['#prefix'] = '<div id="month" class="tab-pane fade">';
        $form['tab-content']['month']['#suffix'] = '</div>';
        $form['tab-content']['year']['#prefix']  = '<div id="year" class="tab-pane fade">';
        $form['tab-content']['year']['#suffix']  = '</div>';
        $form['tab-content']['full']['#prefix']  = '<div id="full" class="tab-pane fade">';
        $form['tab-content']['full']['#suffix']  = '</div>';

        return $form;
        return [
            '#type' => 'item',
            '#markup' => '<div id="statistic"></div>',
            '#attached' => array(
                'library' => array(
                    'crypto_page/statistic'
                ),
            ),
        ];
    }
}