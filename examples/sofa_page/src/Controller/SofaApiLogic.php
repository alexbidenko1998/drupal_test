<?php

namespace Drupal\sofa_page\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\MultipartStream;
use Symfony\Component\HttpFoundation\RedirectResponse;
class SofaApiLogic
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

    public static function send($method, $data = array(), $type = 'POST', $path = null, $returnType = 'array', $restartToken = 0)
    {
		$userData = \Drupal::service('user.data');
		//$id = \Drupal::currentUser()->id();
		$name = \Drupal::currentUser()->getUsername();
		
		if($name === 'test2'){
			if ($restartToken === 1)
				\Drupal::state()->set('token', self::getToken());
			$token = \Drupal::state()->get('token');
		}
		else
			$token = $userData->get('sofa', 1, 'token');
       
        $url = self::getMethodUrl($method);

        $client = new Client();
		
		if($path != null)
			$url = $url.'/'.$path;
		
		if(($data != []) && ($type != 'POST_MULT')){
			$data_string = json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
		}else{
			$data_string = "{}";
		}
		drupal_set_message(['#type' => 'item', '#markup' => print_r($url, true),]);
		drupal_set_message(['#type' => 'item', '#markup' => print_r($data_string, true),]);
		try{
			switch ($type) {
				case 'GET':
					$response = $client->get($url, [
					'headers' => [
						'Accept' => 'application/json;charset="utf-8"',
						'Content-Type' => 'application/json;charset="utf-8"',
						'Authorization' => 'Bearer ' . $token,
					]
					]);
					$content = $response->getBody();
					break;
				case 'POST':
					$response =$client->request($type, $url, [
					'headers' => [
						'Accept' => 'application/json;charset="utf-8"',
						'Content-Type' => 'application/json;charset="utf-8"',
						'Authorization' => 'Bearer ' . $token,
					],
					'body' => $data_string
					]);
					$content = $response->getBody();
					break;
				case 'PUT':
					$response =$client->request($type, $url, [
					'headers' => [
						'Accept' => 'application/json;charset="utf-8"',
						'Content-Type' => 'application/json;charset="utf-8"',
						'Authorization' => 'Bearer ' . $token,
					],
					'body' => $data_string
					]);
					$content = $response->getBody();
					break;
				case 'DELETE':
					$response =$client->request($type, $url, [
					'headers' => [
						'Accept' => 'application/json;charset="utf-8"',
						'Content-Type' => 'application/json;charset="utf-8"',
						'Authorization' => 'Bearer ' . $token,
					]
					]);
					$content = $response->getBody();
					break;
				case 'POST_MULT':
					drupal_set_message(['#type' => 'item', '#markup' => print_r($data, true),]);
					$response =$client->request('POST', $url, [
					'headers' => [
						'Accept' => 'multipart/form-data; boundary=6o2knFse3p53ty9dmcQvWAIx1zInP11uCfbm',
						'Content-Type' => 'multipart/form-data; boundary=6o2knFse3p53ty9dmcQvWAIx1zInP11uCfbm',
						'Authorization' => 'Bearer ' . $token,
					],
					'body' => new MultipartStream($data, '6o2knFse3p53ty9dmcQvWAIx1zInP11uCfbm')
					]);
					$content = $response->getBody();
					break;
			}
		} catch (RequestException $exception) {
			$statusCode = $exception->getResponse()->getStatusCode();
			switch ($statusCode){
				case 401:
					$result = self::send($method, $data, $type, $path, $returnType, 1);
					break;
				case 403:
					if(($method != 'getToken') && ($restartToken === 0))
						$result = self::send($method, $data, $type, $path, $returnType, 1);
					break;
				default:
					drupal_set_message(['#type' => 'item', '#markup' => print_r('Error code: '.$statusCode, true),]);
					return ['errorCode' => $statusCode];
					break;
				
			}
		}
		
        if ($returnType == 'object')
            $result = json_decode($content);
        else if ($returnType == 'json')
            $result = $content;
        else
            $result = (array)json_decode($content, true);
		drupal_set_message(['#type' => 'item', '#markup' => print_r($result, true),]);
		
		drupal_set_message(['#type' => 'item', '#markup' => print_r(json_encode($result, JSON_PRETTY_PRINT), true),]);
        return $result;
    }

    public static function getToken()
    {
        $url = self::getMethodUrl('getToken');
        $array = array(
            'login' => 'Admin',
            'password' => 'Admin'
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
		//drupal_set_message(['#type' => 'item', '#markup' => print_r($result, true),]);
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
	
	public function csvtoarray($filename='', $delimiter){

		if(!file_exists($filename) || !is_readable($filename)) return FALSE;
		$header = NULL;
		$data = array();

		if (($handle = fopen($filename, 'r')) !== FALSE ) {
		  while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
		  {
			if(!$header){
			  $header = $row;
			}else{
			  $data[] = array_combine($header, $row);
			}
		  }
		  fclose($handle);
		}

		return $data;
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
        if ($get === []){
			$url = \Drupal::request()->getRequestUri();
		}
        else
            $url = \Drupal::url('sofa_page.user_list', $get, ['absolute' => TRUE]);
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

    public static function getImageUrl()
    {
        return self::$image_url;
    }

    private static $_url = 'http://souz-m.tk/api/v2';
    private static $image_url = 'http://souz-m.tk';
    const API_ADDRESS = 'http://souz-m.tk/api/v2';


    private static $requestInfo = [
        'getToken' => [
            'url' => '/auth/login',
        ],
        'Users' => [
            'url' => '/users',
        ],
        'Roles' => [
            'url' => '/admin/roles',
        ],
        'Permissions' => [
            'url' => '/admin/permissions',
        ],
        'Sofas' => [
            'url' => '/resources/sofas',
        ],
        'Fabrics' => [
            'url' => '/resources/fabrics',
        ],
        'Collections' => [
            'url' => '/resources/collections',
        ],
        'Distributors' => [
            'url' => '/distributors',
        ],
        'Manufacturers' => [
            'url' => '/manufacturers',
        ],
        'Projects' => [
            'url' => '/projects',
        ],
        'Handbooks' => [
            'url' => '/resources/handbooks',
        ],
    ];

}