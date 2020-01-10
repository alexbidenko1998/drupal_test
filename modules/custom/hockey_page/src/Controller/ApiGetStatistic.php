<?php
/**
 * @file
 * Contains \Drupal\mypage\Controller\MyPageController.
 */

namespace Drupal\hockey_page\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiGetStatistic extends JsonResponse  {
  // Название переменной такоже как в роуте!!!


  public function content() {

    $method = 'GetStatistic';
    $data = [
      "period" => "day",
      "type"   => "accounts_created"
    ];

    if(isset($_GET['type'])){ $data['type'] = $_GET['type']; }
    if(isset($_GET['periodStart'])){ $data['startPeriod'] = (int) ($_GET['periodStart']/1000); }
    if(isset($_GET['periodEnd']))  { $data['endPeriod']   = (int) ($_GET['periodEnd']/1000); }

    if(isset($_GET['statisticType'])){
      if($_GET['statisticType'] == '#month') {$data['period'] = 'month'; }
      if($_GET['statisticType'] == '#year')  {$data['period'] = 'year';  }
      if($_GET['statisticType'] == '#full')  {$data['period'] = 'full';  }
    }

    $result = HockeyApiLogic::sendAR($method,$data, 'array');

    $_result = [
      'request' => $data,
      'title' => '',
      'categories' => [],
      'statistic' => [],
    ];
    if( isset($result['rows'][0]['cells']) ) {
      foreach ($result['rows'][0]['cells'] as $categories) {
        $_result['categories'][] = $categories['date'];
      }
      foreach ($result['rows'] as $res) {
        $_cell = ['name' => $res['title'], 'data' => [] ];
        foreach ($res['cells'] as $cell) {
          $_cell['data'][] = $cell['count'];
        }
        $_result['statistic'][] = $_cell;
      }
    }

    return new JsonResponse($_result, 200, array('Content-Type'=> 'application/json') );
  }
}