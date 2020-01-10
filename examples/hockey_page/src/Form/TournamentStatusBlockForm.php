<?php
/**
 * Created by PhpStorm.
 * User: saint
 * Date: 05.03.2018
 * Time: 19:02
 */

namespace Drupal\hockey_page\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\hockey_page\Controller\HockeyApiLogic;

class TournamentStatusBlockForm extends FormBase {
    /**
     * {@inheritdoc}.
     */
    // Метод для котороый возвращает ид формы.
    public function getFormId() {
        return 'tournament_status_block_form';
    }

    /**
     * {@inheritdoc}.
     */
    // Вместо hook_form.
    public function buildForm(array $form, FormStateInterface $form_state) {

        $error = [ '#type' => 'item', '#markup' => 'Error',];

        $method = 'getTournamentStatus';
        $data = [];
        $result = HockeyApiLogic::send($method, $data);

        if ($result == NULL) {
            return [
                '#type' => 'item',
                '#markup' => $error,
            ];
        }

        $settings = array(
            'unixtimestamp1' => strtotime(date_default_timezone_get()) + $result['monthMatchTimeNA'],
            'unixtimestamp2' => strtotime(date_default_timezone_get()) + $result['monthMatchTimeE'],
            'unixtimestamp3' => strtotime(date_default_timezone_get()) + $result['monthMatchTimeF'],
            'unixtimestamp4' => strtotime(date_default_timezone_get()) + $result['monthMatchTimeS'],
            'unixtimestamp5' => strtotime(date_default_timezone_get()) + $result['monthMatchTimeC'],
            'unixtimestamp6' => strtotime(date_default_timezone_get()) + $result['monthTime'],
            'unixtimestamp7' => strtotime(date_default_timezone_get()) + $result['hour4Time'],
            'unixtimestamp8' => strtotime(date_default_timezone_get()) + $result['hour12Time'],
            'unixtimestamp9' => strtotime(date_default_timezone_get()) + $result['hour23Time'],
            'fontsize' => 20,
        );

        $form['monthStatus'] = array(
            '#type' => 'label',
            '#title' => 'Статус ежемесячного турнира: '.$result['monthStatus'],
        );

        $form['monthMatch_label_1'] = array(
            '#type' => 'label',
            '#title' => 'До следущего матча Северная Америка:',
        );
        $form['monthMatch_timer_1']['content'] = array();
        $form['monthMatch_timer_1']['content'] = ['#markup' => '<div id="jquery-countdown-timer-1"></div>',];
        $form['monthMatch_timer_1']['#attached']['library'][] = 'hockey_page/countdown.timer';
        $form['monthMatch_timer_1']['#attached']['drupalSettings']['countdown'] = $settings;

        $form['monthMatch_label_2'] = array(
            '#type' => 'label',
            '#title' => 'До следущего матча Европа:',
        );
        $form['monthMatch_timer_2']['content'] = array();
        $form['monthMatch_timer_2']['content'] = ['#markup' => '<div id="jquery-countdown-timer-2"></div>',];
        $form['monthMatch_timer_2']['#attached']['library'][] = 'hockey_page/countdown.timer';
        $form['monthMatch_timer_2']['#attached']['drupalSettings']['countdown'] = $settings;

        $form['monthMatch_label_3'] = array(
            '#type' => 'label',
            '#title' => 'До следущего матча Фины:',
        );
        $form['monthMatch_timer_3']['content'] = array();
        $form['monthMatch_timer_3']['content'] = ['#markup' => '<div id="jquery-countdown-timer-3"></div>',];
        $form['monthMatch_timer_3']['#attached']['library'][] = 'hockey_page/countdown.timer';
        $form['monthMatch_timer_3']['#attached']['drupalSettings']['countdown'] = $settings;

        $form['monthMatch_label_4'] = array(
            '#type' => 'label',
            '#title' => 'До следущего матча Шведы:',
        );
        $form['monthMatch_timer_4']['content'] = array();
        $form['monthMatch_timer_4']['content'] = ['#markup' => '<div id="jquery-countdown-timer-4"></div>',];
        $form['monthMatch_timer_4']['#attached']['library'][] = 'hockey_page/countdown.timer';
        $form['monthMatch_timer_4']['#attached']['drupalSettings']['countdown'] = $settings;

        $form['monthMatch_label_5'] = array(
            '#type' => 'label',
            '#title' => 'До следущего матча Чехия:',
        );
        $form['monthMatch_timer_5']['content'] = array();
        $form['monthMatch_timer_5']['content'] = ['#markup' => '<div id="jquery-countdown-timer-5"></div>',];
        $form['monthMatch_timer_5']['#attached']['library'][] = 'hockey_page/countdown.timer';
        $form['monthMatch_timer_5']['#attached']['drupalSettings']['countdown'] = $settings;

        $form['monthMatch_label_6'] = array(
            '#type' => 'label',
            '#title' => 'До следущего этапа:',
        );
        $form['monthMatch_timer_6']['content'] = array();
        $form['monthMatch_timer_6']['content'] = ['#markup' => '<div id="jquery-countdown-timer-6"></div>',];
        $form['monthMatch_timer_6']['#attached']['library'][] = 'hockey_page/countdown.timer';
        $form['monthMatch_timer_6']['#attached']['drupalSettings']['countdown'] = $settings;

        /*$form['monthMatch_button'] = array(
            '#type' => 'submit',
            '#name' => 'monthMatch_button',
            '#value' => 'Следующий матч',
            '#size' => 20,
            '#attributes' => array(
                'button_name' => 'monthMatch_button',
                'class'=>['col-xs-12', 'btn-primary'],
            ),
        );*/
        $form['monthStatus_button'] = array(
            '#type' => 'submit',
            '#name' => 'monthStatus_button',
            '#value' => 'Следующий этап',
            '#size' => 20,
            '#attributes' => array(
                'button_name' => 'monthStatus_button',
                'class'=>['col-xs-12', 'btn-success'],
            ),
        );



        $form['hour4Status'] = array(
            '#type' => 'label',
            '#title' => 'Статус 4-х часовго турнира: '.$result['hour4Status'],
        );

        $form['hour4Status_label_7'] = array(
            '#type' => 'label',
            '#title' => 'До следущего турнира:',
        );
        $form['hour4Status_timer_7']['content'] = array();
        $form['hour4Status_timer_7']['content'] = ['#markup' => '<div id="jquery-countdown-timer-7"></div>',];
        $form['hour4Status_timer_7']['#attached']['library'][] = 'hockey_page/countdown.timer';
        $form['hour4Status_timer_7']['#attached']['drupalSettings']['countdown'] = $settings;

        $form['hour4Status_button'] = array(
            '#type' => 'submit',
            '#name' => 'hour4Status_button',
            '#value' => 'Следующий этап',
            '#attributes' => array(
                'button_name' => 'hour4Status_button',
                'class'=>['col-xs-12', 'btn-success'],
            ),
        );



        $form['hour12Status'] = array(
            '#type' => 'label',
            '#title' => 'Статус 12-х часовго турнира: '.$result['hour12Status'],
        );

        $form['hour12Status_label_8'] = array(
            '#type' => 'label',
            '#title' => 'До следущего турнира:',
        );
        $form['hour12Status_timer_8']['content'] = array();
        $form['hour12Status_timer_8']['content'] = ['#markup' => '<div id="jquery-countdown-timer-8"></div>',];
        $form['hour12Status_timer_8']['#attached']['library'][] = 'hockey_page/countdown.timer';
        $form['hour12Status_timer_8']['#attached']['drupalSettings']['countdown'] = $settings;

        $form['hour12Status_button'] = array(
            '#type' => 'submit',
            '#name' => 'hour12Status_button',
            '#value' => 'Следующий этап',
            '#attributes' => array(
                'button_name' => 'hour12Status_button',
                'class'=>['col-xs-12', 'btn-success'],
            ),
        );



        $form['hour23Status'] = array(
            '#type' => 'label',
            '#title' => 'Статус 23-х часовго турнира: '.$result['hour23Status'],
        );


        $form['hour23Status_label_9'] = array(
            '#type' => 'label',
            '#title' => 'До следущего турнира:',
        );
        $form['hour23Status_timer_9']['content'] = array();
        $form['hour23Status_timer_9']['content'] = ['#markup' => '<div id="jquery-countdown-timer-9"></div>',];
        $form['hour23Status_timer_9']['#attached']['library'][] = 'hockey_page/countdown.timer';
        $form['hour23Status_timer_9']['#attached']['drupalSettings']['countdown'] = $settings;

        $form['hour23Status_button'] = array(
            '#type' => 'submit',
            '#name' => 'hour23Status_button',
            '#value' => 'Следующий этап',
            '#attributes' => array(
                'button_name' => 'hour23Status_button',
                'class'=>['col-xs-12', 'btn-success'],
            ),
        );

        $form['nextDay_button'] = array(
            '#type' => 'submit',
            '#name' => 'nextDay_button',
            '#value' => 'Следующий день',
            '#attributes' => array(
                'button_name' => 'nextDay_button',
                'class'=>['col-xs-12', 'btn-info'],
            ),
        );

        $form['nextMonth_button'] = array(
            '#type' => 'submit',
            '#name' => 'nextMonth_button',
            '#value' => 'Следующий месяц',
            '#attributes' => array(
                'button_name' => 'nextMonth_button',
                'class'=>['col-xs-12', 'btn-info'],
            ),
        );

        $form['#prefix'] = '<div class="row"><div class="col-xs-12">';
        $form['#suffix'] = '</div></div>';

        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        $trigger = $form_state->getTriggeringElement();

        if($trigger['#attributes']['button_name'] == 'nextDay_button') {
            $data = [];
            $result = HockeyApiLogic::send('nextDay', $data);
        }
		else if($trigger['#attributes']['button_name'] == 'nextMonth_button') {
            $data = [];
            $result = HockeyApiLogic::send('nextMonth', $data);
        }
        else if($trigger['#attributes']['button_name'] != 'monthMatch_button') {
            if ($trigger['#attributes']['button_name'] == 'monthStatus_button')
                $data = [
                    'type' => 'month'
                ];
            else if ($trigger['#attributes']['button_name'] == 'hour4Status_button')
                $data = [
                    'type' => 'hour4'
                ];
            else if ($trigger['#attributes']['button_name'] == 'hour12Status_button')
                $data = [
                    'type' => 'hour12'
                ];
            else if ($trigger['#attributes']['button_name'] == 'hour23Status_button')
                $data = [
                    'type' => 'hour23'
                ];
				
            $result = HockeyApiLogic::send('tournamentNextStatus', $data);
        }
        else{
            $data = [];
            $result = HockeyApiLogic::send('tournamentNextMatch', $data);
        }


        if ($result === array()) {
            HockeyApiLogic::my_goto("Успешно", $_GET);
        } else {
            HockeyApiLogic::my_goto(['#type' => 'item', '#markup' => print_r($result, true)], $_GET);
        }
    }

}