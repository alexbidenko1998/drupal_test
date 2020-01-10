<?php
/**
 * Created by PhpStorm.
 * User: saint
 * Date: 13.02.2018
 * Time: 15:17
 */

namespace Drupal\hockey_page\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;

class SettingsTableInfo extends ControllerBase {
    // Название переменной такоже как в роуте!!!
    public function content($settingName)
    {
        $error = [ '#type' => 'item', '#markup' => 'Error',];

        $method = 'getJsonDocument';
        $data = ['name' => $settingName];
        $result = HockeyApiLogic::send($method, $data, 'object');

        if(empty($result)){
            return $error;
        }

        return [
            $this->_render($result, $settingName, 0),
            // $this->getTableKey($result->result, $settingName, 0),
        ];
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
        // drupal_set_message('_render_array');
        // drupal_set_message(['#type' => 'item', '#markup' => '<pre>'.print_r($items,true).'</pre>']);

        $table = [
            '#type' => 'table',
            '#caption' => $key,
            ];
        if($level > 1) {
            unset($table['#caption']);
        }

        if($level > 1 && is_array($items) && $items === array_values($items)){
            // drupal_set_message(['#type' => 'item', '#markup' => '<pre>'.print_r($items,true).'</pre>']);
            foreach ($items as $k => $item) {
                $table['col_'.$k][] = $this->_render($item, $k, $level);
            }
            return $table;
        }

        $defaultRow = $this->getTableKey($items);


        foreach ($items as $k => $item) {
            $type = $this->getItemType($item);
            $row = $defaultRow;
            if ($type === 'object') {
                foreach ($item as $kk => $it) {
                    $row[$kk] = $this->_render($it, $kk, $level);
                }
                $table[] = $row;
            } else {
                if(is_numeric($k)){
                    $row["{$type}_{$k}"] = $this->_render($item, $k, $level);
                } else {
                    $row[$k] = $this->_render($item, $k, $level);
                }
                // $row["{$type}_{$k}"] = $this->_render($item, $k);

                $table[] = $row;
            }
        }

        if($defaultRow !== [] ){
            $table['#header'] = array_keys($defaultRow);
        }
        return $table;
    }
}