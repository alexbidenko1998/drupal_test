<?php

namespace Drupal\hockey_page\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;

class HockeyApiLogic
{
    public static function getMethodUrl($method)
    {
        if (isset(self::$requestInfo[$method])) {
            return self::API_ADDRESS . self::$requestInfo[$method]['url'];
        }
        return 'localhost';
    }

    public static function getMethodUrl_test($method)
    {
        if (isset(self::$requestInfo[$method])) {
            return self::API_ADDRESS_test . self::$requestInfo[$method]['url'];
        }
        return 'localhost';
    }

    /**
     * @param string $method
     * @param array $data
     * @param string $returnType
     * @return array|mixed|null
     */
    public static function send($method, $data = array(), $returnType = 'array', $restartToken = 0)
    {
        if ($restartToken === 1)
            \Drupal::state()->set('token', self::getToken());
        $token = \Drupal::state()->get('token');
        $url = self::getMethodUrl($method);

        if ($data === array()) {
            $data_string = '{}';
        } else {
            $data_string = json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
        }
		
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Accept: application/json;charset="utf-8"',
                'Content-Type: application/json;charset="utf-8"',
                'token: ' . $token,
            ]
        );
        $content = curl_exec($ch);
        // $response = curl_getinfo( $ch );
        curl_close($ch);

        if ($returnType == 'object')
            $result = json_decode($content);
        else if ($returnType == 'json')
            $result = $content;
        else
            $result = (array)json_decode($content, true);


        $result_ch = (array)json_decode($content, true);;
        if (isset($result_ch['status']))
            if($result_ch['status'] === 500)
                $result = NULL;

        if (json_last_error() != 0)
            $result = NULL;

        if(($restartToken === 0) && ($result === NULL)){
            $result = self::send($method, $data,'array', 1);
        }

        return $result;
    }

    public static function getToken()
    {
        $url = self::getMethodUrl('getToken');
        $array = array(
            'login' => 'admin',
            'password' => 'f6fdffe48c111deb0f4c3bd36c032e72'
        );
        $data_string = json_encode($array, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Accept: application/json;charset="utf-8"',
                'Content-Type: application/json;charset="utf-8"',
            ]
        );
        $content = curl_exec($ch);
        $result = (array)json_decode($content, true);
        
        if (json_last_error() != 0)
            $result = NULL;
        else
            $result = $result['token'];
        if ($result === NULL)
            $result = \Drupal::state()->get('token');
        return $result;
    }

    public static function filter($filter, $filterValue, $filterType, $array){
        $filteredArray = [];
        for ($i = 0; $i < count($array); $i++){
            if (isset($array[$i][$filter])){
                if($filterType === 'Contain')
                    if((mb_stripos($array[$i][$filter], $filterValue) === 0) || (mb_stripos($array[$i][$filter], $filterValue) != null))
                        $filteredArray[] = $array[$i];
                if($filterType === 'Equals')
                    if($array[$i][$filter] == $filterValue)
                        $filteredArray[] = $array[$i];
                if($filterType === 'NotEquals')
                    if($array[$i][$filter] != $filterValue)
                        $filteredArray[] = $array[$i];

            }
        }
        return $filteredArray;
    }


    public static function filterSubkey($filter, $filter2,$filterValue, $filterType, $array){
        $filteredArray = [];
        for ($i = 0; $i < count($array); $i++){
            if (isset($array[$i][$filter][$filter2])){
                if($filterType === 'Contain')
                    if((mb_stripos($array[$i][$filter][$filter2], $filterValue) === 0) || (mb_stripos($array[$i][$filter][$filter2], $filterValue) != null))
                        $filteredArray[] = $array[$i];
                if($filterType === 'Equals')
                    if($array[$i][$filter][$filter2] == $filterValue)
                        $filteredArray[] = $array[$i];
                if($filterType === 'NotEquals')
                    if($array[$i][$filter][$filter2] != $filterValue)
                        $filteredArray[] = $array[$i];

            }
        }
        return $filteredArray;
    }


    public static function filterNumber($filter, $filterValue, $array){
        $filterNumber = null;
        for ($i = 0; $i < count($array); $i++){
            if (isset($array[$i][$filter])){
                if($array[$i][$filter] == $filterValue)
                    $filterNumber = $i;

            }
        }
        return $filterNumber;
    }

    public static function sortBySubkey($array, $subkey, $sortType = SORT_ASC) {
        foreach ($array as $subarray) {
            if (isset($subarray[$subkey]))
                $keys[] = $subarray[$subkey];
            else
                $keys[] = '';
        }
        array_multisort($keys, $sortType, SORT_NATURAL | SORT_FLAG_CASE, $array);
        return $array;
    }

    public static function my_goto($message, $get = []) {
        if ($get === [])
            $url = \Drupal::service('path.current')->getPath();
        else
            $url = \Drupal::url('hockey_page.player_list', $get, ['absolute' => TRUE]);
        $response = new RedirectResponse($url);
        $response->send();
        drupal_set_message($message);
        exit;
    }

    public static function getServerUrl()
    {
        return self::$_url;
    }

    public static function getServerUrl_test()
    {
        return self::$_url;
    }

    private static $_url = 'http://185.17.3.211:8090';
    const API_ADDRESS = 'http://185.17.3.211:8090/api/';


    private static $requestInfo = [
        'getToken' => [
            'url' => 'editor.auth_login',
        ],
        'documentNames' => [
            'url' => 'editor.get_document_names',
        ],
        'getJsonDocument' => [
            'url' => 'editor.get_json_document',
        ],
        'setJsonDocument' => [
            'url' => 'editor.set_json_document',
        ],
        'getKitInfo' => [
            'url' => 'editor.get_kit_info',
        ],
        'getTournaments' => [
            'url' => 'editor.get_tournaments',
        ],
        'getTournamentInfo' => [
            'url' => 'editor.get_tournament_info',
        ],
        'getTournamentStatus' => [
            'url' => 'editor.get_tournament_status',
        ],
        'tournamentNextMatch' => [
            'url' => 'editor.tournament_next_match',
        ],
        'tournamentNextStatus' => [
            'url' => 'editor.tournament_next_status',
        ],
        'nextDay' => [
            'url' => 'editor.next_day',
        ],
        'nextMonth' => [
            'url' => 'editor.next_month',
        ],
        'sendMessage' => [
            'url' => 'editor.send_message',
        ],
        'addLegendaryPlayer' => [
            'url' => 'editor.add_legendary_player',
        ],
        'getLegendaryInfo' => [
            'url' => 'editor.get_legendary_info',
        ],
        'getLegendaryPlayers' => [
            'url' => 'editor.get_legendary_players',
        ],
        'updateLegendaryPlayers' => [
            'url' => 'editor.update_legendary_players',
        ],
        'getPlayers' => [
            'url' => 'editor.get_players',
        ],
        'getPlayerInfo' => [
            'url' => 'editor.get_player_info',
        ],
        'getAccounts' => [
            'url' => 'editor.get_accounts',
        ],
        'getAccountInfo' => [
            'url' => 'editor.get_account_info',
        ],
        'accountUpdate' => [
            'url' => 'editor.account_update',
        ],
        'accountAddMoney' => [
            'url' => 'editor.account_add_money',
        ],
        'accountAddExp' => [
            'url' => 'editor.account_add_exp',
        ],
        'accountAddToken' => [
            'url' => 'editor.account_add_token',
        ],
        'accountAddItem' => [
            'url' => 'editor.account_add_item',
        ],
        'accountAddLegendary' => [
            'url' => 'editor.account_add_legendary',
        ],
        'accountAddPlayer' => [
            'url' => 'editor.account_add_player',
        ],
        'accountDelete' => [
            'url' => 'editor.account_delete',
        ],
        'accountChangeName' => [
            'url' => 'editor.account_change_name',
        ],
        'reloadConfig' => [
            'url' => 'editor.reload_all_configs',
        ],
        'reloadOneConfig' => [
            'url' => 'editor.reload_config',
        ],
    ];

}