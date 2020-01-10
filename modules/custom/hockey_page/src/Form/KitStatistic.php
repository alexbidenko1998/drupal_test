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

class KitStatistic extends FormBase {
    /**
     * {@inheritdoc}.
     */
    // Метод для котороый возвращает ид формы.
    public function getFormId() {
        return 'UserAddItem_form';
    }

    /**
     * {@inheritdoc}.
     */
    // Вместо hook_form.
    public function buildForm(array $form, FormStateInterface $form_state) {

        $empty = 'Не удалось получить данные';

        $error = [ '#type' => 'item', '#markup' => 'Error',];

        $method = 'getKitInfo';
        $data = ['name' =>'kit'];
        $result = HockeyApiLogic::send($method, $data, 'array');

        if(empty($result)){
            return $error;
        }
		
        $form['result'] = array(
            '#type' => 'hidden',
            '#default_value' => json_encode($result['boughtKits'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        );
		
        $data_string = json_encode($result['boughtKits'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
        $result = json_decode($data_string);
	
        /*$header = array(
            'Год',
            'Месяц',
            'Тип комплекта',
            'Ранг лиги',
            'Количество',
        );*/
		
        $header = array(
            '_________________________________________________________________________',
            'Ранг лиги',
            'Количество',
        );
		
        $form['header'] = array(
            '#type' => 'table',
            // '#caption' => $this->t('Sample Table'),
            '#header' => $header,
        );
		
        $form['table'] = [
            $this->_render($result, '', 0),
            // $this->getTableKey($result->result, $settingName, 0),
        ];
		
        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => 'Скачать',
            '#attributes' => [
                'class' => ['col-xs-12']
            ]
        );
        $form['#prefix'] = '<div class="row"><div class="col-xs-12">';
        $form['#suffix'] = '</div></div>';

        return $form;
    }

    private function getItemType($item){
        $type = gettype($item);
        switch ($type) {
            case 'integer':
            case 'double':
            case 'float':
            case 'string':
                $type = 'string';
                break;
            case 'boolean':
            case 'NULL':
                $type = 'bool';
                break;
            case 'array':
                $type = 'array';
                break;
            case 'object':
                $type = 'object';
                break;
            default:
                $type = 'undefened';
                break;
        }
        return $type;
    }
    private function _render($item, $key, $level){
        $level++;
        $funcName = '_render_' . $this->getItemType($item);
        // drupal_set_message($funcName);
        return $this->$funcName($item, $key, $level);
    }
    private function _render_string($item, $key, $level){
        return ['#type' => 'item', '#markup' => $item,];
    }
    private function _render_bool($item, $key, $level){
        return ['#type' => 'item', '#markup' => $item === true ? 'TRUE' : $item === false ? 'FALSE' : 'NULL',];
    }
    private function _render_object($item, $key, $level){
		
		$table = [ '#type' => 'table', '#caption' => $key];
	
        if($level > 1) {
            unset($table['#caption']);
        }
        foreach ($item as $_key => $_item){
            $table[] = [
                ['#type' => 'item', '#markup' => $_key,],
                $this->_render($_item, $_key, $level),
            ];//
        }
        // $table = $this->_render_array((array)$item, $key, $level);
        // unset($table['#caption']);
        return $table;
    }
    private function _render_undefened($item, $key, $level){
        return ['#type' => 'item', '#markup' => 'Undefened',];
    }

    // получить список полей таблицы
    private function getTableKey($items){
        $defaultRow = [];
        foreach ($items as $k => $item) {
            $type = $this->getItemType($item);
            if ($type === 'object') {
                foreach ($item as $kk => $it) {
                    $defaultRow[$kk] = ['#type' => 'item', '#markup' => '-'];
                }
            } else {
                if(is_numeric($k)){
                    $defaultRow["{$type}_{$k}"] = ['#type' => 'item', '#markup' => '-'];
                } else {
                    $defaultRow[$k] = ['#type' => 'item', '#markup' => '-'];
                }
            }
        }
        return $defaultRow;
    }


    private function _render_array($items, $key, $level){
		
	}

    public function submitForm(array &$form, FormStateInterface $form_state) {
		
        $result    = $form_state->getValue('result');
        $result = (array) json_decode ($result, true);
		
		//drupal_set_message(['#type' => 'item', '#markup' => print_r($result['boughtKits'], true),]);
		//drupal_set_message(['#type' => 'item', '#markup' => print_r($data_string, true),]);

		header("Content-Type: text/csv");
		header("Content-disposition: attachment; filename= kit_statistic.csv");
		header('Pragma: no-cache');
		header("Expires: 0");
	
		$fh = fopen('php://output', 'wb');
		
		 
		  //записать данные в формате CSV
		foreach (array_keys($result) as $keyYear){
			fputcsv($fh, [$keyYear]);
			foreach (array_keys($result[$keyYear]) as $keyMonth){
				fputcsv($fh,[$keyMonth]);
				foreach (array_keys($result[$keyYear][$keyMonth]) as $keyType){
					fputcsv($fh,[$keyType]);
					foreach (array_keys($result[$keyYear][$keyMonth][$keyType]) as $keyGrade){
						fputcsv($fh,[$keyGrade,$result[$keyYear][$keyMonth][$keyType][$keyGrade]]);
					}
				}
			}
		}

		  //закрываем поток
		fclose($fh);
		die();
    }

}