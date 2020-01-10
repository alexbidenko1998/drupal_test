<?php
/**
 * Created by PhpStorm.
 * User: saint
 * Date: 05.03.2018
 * Time: 19:02
 */

namespace Drupal\sofa_page\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\sofa_page\Controller\SofaApiLogic;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;

class SofaEdit extends FormBase {

    /**
     * {@inheritdoc}.
     */
    // Метод для котороый возвращает ид формы.
    public function getFormId() {
        return 'SofaEdit_form';
    }

    /**
     * {@inheritdoc}.
     */
    // Вместо hook_form.
    public function buildForm(array $form, FormStateInterface $form_state, $sofaId = '')
    {

		$method  = 'Sofas';
		$result = SofaApiLogic::send($method, $result, 'GET', $sofaId);


        $form['#tree'] = TRUE;
        $form['#prefix'] = '<div id="all-fieldset-wrapper">';
        $form['#suffix'] = '</div>';
		
        $form['id'] = array(
            '#type' => 'hidden',
            '#default_value' => $sofaId,
        );
		
        $form['label'] = array(
            '#type' => 'label',
            '#title' => $sofaId,
        );
		
		
		if(isset($result['image']['url'])){
			$uri = SofaApiLogic::getImageUrl().$result['image']['url'];
			$avatar_url = "<img src=\"{$uri}\" >";
		}

		$form['modelFileSpace'] = [
			'#type' => 'item',
			'#markup' => $this->t('     '),
		];
		
        $form['modelFile'] = array(
            '#type' => 'label',
            '#title' => 'Модель: '.$result['modelFile']['name'],
        );
		
        $form['image'] = array(
            '#type' => 'item',
            '#markup' => $avatar_url,
        );

        $form['name'] = array(
            '#type' => 'textfield',
            '#title' => 'Название',
            '#required' => TRUE,
        );
		
        if (isset($result['name']))
            $form['name']['#default_value'] = $result['name'];

        $form['description'] = array(
            '#type' => 'textfield',
            '#title' => 'Описание',
        );
        if (isset($result['description']))
            $form['description']['#default_value'] = $result['description'];

        $form['manufacturerId'] = array(
            '#type' => 'textfield',
            '#title' => 'Фабрика',
        );
        if (isset($result['manufacturerId']))
            $form['manufacturerId']['#default_value'] = $result['manufacturerId'];

        $form['promoteCollectionId'] = array(
            '#type' => 'textfield',
            '#title' => 'Коллекция',
        );
        if (isset($result['promoteCollectionId']))
            $form['promoteCollectionId']['#default_value'] = $result['promoteCollectionId'];

        $form['defaultFabricId'] = array(
            '#type' => 'textfield',
            '#title' => 'Ткань',
        );
        if (isset($result['defaultFabricId']))
            $form['defaultFabricId']['#default_value'] = $result['defaultFabricId'];

        $form['transformationType'] = array(
            '#type' => 'textfield',
            '#title' => 'Механизм трансформации',
        );
        if (isset($result['transformationType']))
            $form['transformationType']['#default_value'] = $result['transformationType'];

        $form['typeOfFurniture'] = array(
            '#type' => 'textfield',
            '#title' => 'Тип мебели',
        );
        if (isset($result['typeOfFurniture']))
            $form['typeOfFurniture']['#default_value'] = $result['typeOfFurniture'];



        $form['parts'] = [
            '#type' => 'fieldset',
            '#title' => 'Части',
            '#prefix' => '<div id="parts-fieldset-wrapper">',
            '#suffix' => '</div>',
            '#collapsible' => true,
            //'#collapsed'   => true,
        ];
		
		$parts = $form_state->get('parts');
		if ($parts === NULL) {
			if(isset($result['parts']))
				$parts = count($result['parts']);
			else
				$parts = 0;
			$form_state->set('parts', $parts);
		}
		
		for ($j = 0; $j < $parts; $j++) {
			$form['parts'][$j] = [
				'#type' => 'fieldset',
				'#title' => '- ' . $j,
				'#prefix' => '<div id="parts-fieldset-' . $j . '-wrapper">',
				'#suffix' => '</div>',
				'#collapsible' => true,
				//'#collapsed'   => true,
			];

			$form['parts'][$j]['partName'] = array(
				'#type' => 'textfield',
				'#title' => 'Название',
			);
			if (isset($result['parts'][$j]['partName']))
				$form['parts'][$j]['partName']['#default_value'] = $result['parts'][$j]['partName'];

			$form['parts'][$j]['fabricId'] = array(
				'#type' => 'textfield',
				'#title' => 'fabricId',
			);
			if (isset($result['parts'][$j]['fabricId']))
				$form['parts'][$j]['fabricId']['#default_value'] = $result['parts'][$j]['fabricId'];

			$form['parts'][$j]['tiling'] = [
				'#type' => 'fieldset',
				'#title' => 'tiling',
				'#prefix' => '<div id="size_fieldset-wrapper">',
				'#suffix' => '</div>',
				'#collapsible' => true,
				//'#collapsed'   => true,
			];

			$form['parts'][$j]['tiling']['x'] = array(
				'#type' => 'textfield',
				'#title' => 'X',
				'#attributes' => array(
					' type' => 'number',
				),
			);
			if (isset($result['parts'][$j]['tiling']['x']))
				$form['parts'][$j]['tiling']['x']['#default_value'] = $result['parts'][$j]['tiling']['x'];

			$form['parts'][$j]['tiling']['y'] = array(
				'#type' => 'textfield',
				'#title' => 'Y',
				'#attributes' => array(
					' type' => 'number',
				),
			);
			if (isset($result['parts'][$j]['tiling']['y']))
				$form['parts'][$j]['tiling']['y']['#default_value'] = $result['parts'][$j]['tiling']['y'];
			
			$form['parts'][$j]['offset'] = [
				'#type' => 'fieldset',
				'#title' => 'Смещение',
				'#prefix' => '<div id="size_fieldset-wrapper">',
				'#suffix' => '</div>',
				'#collapsible' => true,
				//'#collapsed'   => true,
			];

			$form['parts'][$j]['offset']['x'] = array(
				'#type' => 'textfield',
				'#title' => 'X',
				'#attributes' => array(
					' type' => 'number',
				),
			);
			if (isset($result['parts'][$j]['offset']['x']))
				$form['parts'][$j]['offset']['x']['#default_value'] = $result['parts'][$j]['offset']['x'];

			$form['parts'][$j]['offset']['y'] = array(
				'#type' => 'textfield',
				'#title' => 'Y',
				'#attributes' => array(
					' type' => 'number',
				),
			);
			if (isset($result['parts'][$j]['offset']['y']))
				$form['parts'][$j]['offset']['y']['#default_value'] = $result['parts'][$j]['offset']['y'];

			$form['parts'][$j]['angle'] = array(
				'#type' => 'textfield',
				'#title' => 'Угол',
				'#attributes' => array(
					' type' => 'number',
				),
			);
			if (isset($result['parts'][$j]['angle']))
				$form['parts'][$j]['angle']['#default_value'] = $result['parts'][$j]['angle'];

		}

		$form['parts']['action'] = [
			'#type' => 'item',
			'#markup' => $this->t('     '),
		];

		$form['parts']['actions'] = [
			'#type' => 'actions',
		];

		$form['parts']['actions']['add_part'] = [
			'#type' => 'submit',
			'#name' => 'add_part',
			'#value' => '',
			'#submit' => ['::addOne'],
			'#ajax' => [
				'callback' => '::changeCallback',
				'wrapper' => 'parts-fieldset-wrapper',
			],
			'#limit_validation_errors' => array(), 
			'#attributes' => [
				'class' => ['glyphicon glyphicon-plus']
			],
		];

		if ($parts > 0) {
			$form['parts']['actions']['remove_part'] = [
				'#type' => 'submit',
				'#name' => 'remove_part',
				'#value' => '',
				'#submit' => ['::removeOne'],
				'#ajax' => [
					'callback' => '::changeCallback',
					'wrapper' => 'parts-fieldset-wrapper',
				],
				'#limit_validation_errors' => array(), 
				'#attributes' => [
					'class' => ['glyphicon glyphicon-minus']
				],
			];
		}
		
		



        $form['multipurpose'] = array(
            '#type' => 'select',
            '#title' => 'Универсальный',
            '#options' => [
                    'false' => 'Нет',
                    'true' => 'Да',
            ],
            '#empty_option' => '- Выбор -',
        );
        if (isset($result['multipurpose'])){
			$form['multipurpose']['#default_value'] = 'false';
			if($result['multipurpose'] == 1)
				$form['multipurpose']['#default_value'] = 'true';
			if($result['multipurpose'] == '1')
				$form['multipurpose']['#default_value'] = 'true';
			if($result['multipurpose'] == 'true')
				$form['multipurpose']['#default_value'] = 'true';
		}

        $form['furnitureForm'] = array(
            '#type' => 'textfield',
            '#title' => 'Форма мебели',
        );
        if (isset($result['furnitureForm']))
            $form['furnitureForm']['#default_value'] = $result['furnitureForm'];

        $form['furnitureGroup'] = array(
            '#type' => 'textfield',
            '#title' => 'Мебельная группа',
        );
        if (isset($result['furnitureGroup']))
            $form['furnitureGroup']['#default_value'] = $result['furnitureGroup'];

        $form['furnitureStyle'] = array(
            '#type' => 'textfield',
            '#title' => 'Стиль',
        );
        if (isset($result['furnitureStyle']))
            $form['furnitureStyle']['#default_value'] = $result['furnitureStyle'];

        $form['additionalProperties'] = [
            '#type' => 'fieldset',
            '#title' => 'Дополнительные свойства',
            '#prefix' => '<div id="prop-fieldset-wrapper">',
            '#suffix' => '</div>',
            '#collapsible' => true,
            //'#collapsed'   => true,
        ];
		
		
		$prop = $form_state->get('prop');
		if ($prop === NULL) {
			if(isset($result['additionalProperties']))
				$prop = count($result['additionalProperties']);
			else
				$prop = 0;
			$form_state->set('prop', $prop);
		}
		
		for ($j = 0; $j < $prop; $j++) {
			$form['additionalProperties'][$j] = array(
				'#type' => 'textfield',
			);
			if (isset($result['additionalProperties'][$j]))
				$form['additionalProperties'][$j]['#default_value'] = $result['additionalProperties'][$j];
		}

		$form['additionalProperties']['action'] = [
			'#type' => 'item',
			'#markup' => $this->t('     '),
		];

		$form['additionalProperties']['actions'] = [
			'#type' => 'actions',
		];

		$form['additionalProperties']['actions']['add_prop'] = [
			'#type' => 'submit',
			'#name' => 'add_prop',
			'#value' => '',
			'#submit' => ['::addOne'],
			'#ajax' => [
				'callback' => '::changeCallback',
				'wrapper' => 'prop-fieldset-wrapper',
			],
			'#limit_validation_errors' => array(), 
			'#attributes' => [
				'class' => ['glyphicon glyphicon-plus']
			],
		];

		if ($prop > 0) {
			$form['additionalProperties']['actions']['remove_prop'] = [
				'#type' => 'submit',
				'#name' => 'remove_prop',
				'#value' => '',
				'#submit' => ['::removeOne'],
				'#ajax' => [
					'callback' => '::changeCallback',
					'wrapper' => 'prop-fieldset-wrapper',
				],
				'#limit_validation_errors' => array(), 
				'#attributes' => [
					'class' => ['glyphicon glyphicon-minus']
				],
			];
		}
		
		
		
		

        $form['length'] = array(
            '#type' => 'textfield',
            '#title' => 'Длина',
            '#attributes' => array(
                ' type' => 'number',
            ),
        );
        if (isset($result['length']))
            $form['length']['#default_value'] = $result['length'];

        $form['width'] = array(
            '#type' => 'textfield',
            '#title' => 'Ширина',
            '#attributes' => array(
                ' type' => 'number',
            ),
        );
        if (isset($result['width']))
            $form['width']['#default_value'] = $result['width'];

        $form['height'] = array(
            '#type' => 'textfield',
            '#title' => 'Высота',
            '#attributes' => array(
                ' type' => 'number',
            ),
        );
        if (isset($result['height']))
            $form['height']['#default_value'] = $result['height'];

        $form['bedLength'] = array(
            '#type' => 'textfield',
            '#title' => 'Длина спального места',
            '#attributes' => array(
                ' type' => 'number',
            ),
        );
        if (isset($result['bedLength']))
            $form['bedLength']['#default_value'] = $result['bedLength'];

        $form['bedWidth'] = array(
            '#type' => 'textfield',
            '#title' => 'Ширина спального места',
            '#attributes' => array(
                ' type' => 'number',
            ),
        );
        if (isset($result['bedWidth']))
            $form['bedWidth']['#default_value'] = $result['bedWidth'];

        $form['bedHeight'] = array(
            '#type' => 'textfield',
            '#title' => 'Высота спального места',
            '#attributes' => array(
                ' type' => 'number',
            ),
        );
        if (isset($result['bedHeight']))
            $form['bedHeight']['#default_value'] = $result['bedHeight'];

        $form['seatLength'] = array(
            '#type' => 'textfield',
            '#title' => 'Длина посадочного места',
            '#attributes' => array(
                ' type' => 'number',
            ),
        );
        if (isset($result['seatLength']))
            $form['seatLength']['#default_value'] = $result['seatLength'];

        $form['seatWidth'] = array(
            '#type' => 'textfield',
            '#title' => 'Ширина посадочного места',
            '#attributes' => array(
                ' type' => 'number',
            ),
        );
        if (isset($result['seatWidth']))
            $form['seatWidth']['#default_value'] = $result['seatWidth'];

        $form['seatHeight'] = array(
            '#type' => 'textfield',
            '#title' => 'Высота посадочного места',
            '#attributes' => array(
                ' type' => 'number',
            ),
        );
        if (isset($result['seatHeight']))
            $form['seatHeight']['#default_value'] = $result['seatHeight'];

        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => 'Обновить',
            '#attributes' => [
                'class' => ['col-xs-12', 'btn-info']
            ]
        );
        return $form;
    }


    public function changeCallback(array &$form, FormStateInterface $form_state) {
        $trigger = $form_state->getTriggeringElement();
        drupal_set_message(['#type' => 'item', '#markup' => print_r($trigger, true),]);
        if($trigger['#parents'][0] === 'parts')
            return $form['parts'];
        elseif($trigger['#parents'][0] === 'additionalProperties')
            return $form['additionalProperties'];
        else
            return $form;
    }

    public function typeAjax(FormStateInterface $form_state){
        $trigger = $form_state->getTriggeringElement();
        drupal_set_message(['#type' => 'item', '#markup' => print_r($trigger, true),]);
        if($trigger['#ajax']['wrapper'] === 'parts-fieldset-wrapper'){
            $num_res = $form_state->get('parts');
            $num_txt = 'parts';
        }
        elseif($trigger['#ajax']['wrapper'] === 'prop-fieldset-wrapper'){
            $num_res = $form_state->get('prop');
            $num_txt = 'prop';
        }
        return [$num_res, $num_txt];
    }

    public function addOne(array &$form, FormStateInterface $form_state) {
        $arr = $this -> typeAjax($form_state);
        $add_button = $arr[0] + 1;
        $form_state->set($arr[1], $add_button);
        $form_state->setRebuild();
    }

    public function removeOne(array &$form, FormStateInterface $form_state) {
        $arr = $this -> typeAjax($form_state);
        if ($arr[0] > 0) {
            $remove_button = $arr[0] - 1;
            $form_state->set($arr[1], $remove_button);
        }
        $form_state->setRebuild();
    }
	
    /**
     * {@inheritdoc}
     */
    // Вместо hook_form_validate.
    public function validateForm(array &$form, FormStateInterface $form_state){
    }

    /**
     * {@inheritdoc}
     */
    // Вместо hook_form_submit.
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $setting = $form_state->getValues();

        $result = [
			'id' => $setting['id'],
			'name' => $setting['name'],
			'description' => $setting['description'],
			'manufacturerId' => $setting['manufacturerId'],
			'promoteCollectionId' => $setting['promoteCollectionId'],
			'defaultFabricId' => $setting['defaultFabricId'],
			'transformationType' => $setting['transformationType'],
			'typeOfFurniture' => $setting['typeOfFurniture'],
			'parts' => [],
			'multipurpose' => $setting['multipurpose'],
			'furnitureForm' => $setting['furnitureForm'],
			'furnitureGroup' => $setting['furnitureGroup'],
			'furnitureStyle' => $setting['furnitureStyle'],
			'additionalProperties' => [],
			'length' => $setting['length'],
			'width' => $setting['width'],
			'height' => $setting['height'],
			'bedLength' => $setting['bedLength'],
			'bedWidth' => $setting['bedWidth'],
			'bedHeight' => $setting['bedHeight'],
			'seatLength' => $setting['seatLength'],
			'seatWidth' => $setting['seatWidth'],
			'seatHeight' => $setting['seatHeight'],
		];
		
		
		$parts =  $form_state->get('parts');
		$array_parts = array();
		for ($j = 0; $j < $parts; $j++) {
			$array_parts[] = array(
				'partName' => $setting['parts'][$j]['partName'] ?? NULL,
				'fabricId' => $setting['parts'][$j]['fabricId'] ?? NULL,
				'tiling' => [ 
					'x' => $setting['parts'][$j]['tiling']['x'] ?? NULL,
					'y' => $setting['parts'][$j]['tiling']['y'] ?? NULL,
					],
				'offset' => [ 
					'x' => $setting['parts'][$j]['offset']['x'] ?? NULL,
					'y' => $setting['parts'][$j]['offset']['y'] ?? NULL,
					],
				'angle' => $setting['parts'][$j]['angle'] ?? NULL,
			);
		}
		
		$prop =  $form_state->get('prop');
		$array_prop = array();
		for ($j = 0; $j < $prop; $j++) {
			$array_prop[] = $setting['additionalProperties'][$j];
		}

		$result['parts'] = $array_parts;
		$result['additionalProperties'] = $array_prop;
		
		
        $method  = 'Sofas';
        $result = SofaApiLogic::send($method, $result, 'PUT', $setting['id']);
		
        if($result === []){
            drupal_set_message('Успешно');
            //drupal_set_message(['#type' => 'item', '#markup' => print_r($result, true),]);
        } else {
            drupal_set_message('Не обновлено', 'error');
        }
    }

}