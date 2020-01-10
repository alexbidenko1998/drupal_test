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

class ProjectEdit extends FormBase {

    /**
     * {@inheritdoc}.
     */
    // Метод для котороый возвращает ид формы.
    public function getFormId() {
        return 'ProjectEdit_form';
    }

    /**
     * {@inheritdoc}.
     */
    // Вместо hook_form.
    public function buildForm(array $form, FormStateInterface $form_state, $projectId = '')
    {

		$method  = 'Projects';
		$result = SofaApiLogic::send($method, $result, 'GET', $projectId);

        $form['#tree'] = TRUE;
        $form['#prefix'] = '<div id="all-fieldset-wrapper">';
        $form['#suffix'] = '</div>';
		

        $form['id'] = array(
            '#type' => 'hidden',
            '#default_value' => $projectId,
        );
		
        $form['label'] = array(
            '#type' => 'label',
            '#title' => '',
        );
        if (isset($result['id']))
            $form['label']['#title'] = $result['id'];
		
		$form['labelSpace'] = [
			'#type' => 'item',
			'#markup' => $this->t('     '),
		];
		
        $form['description'] = array(
            '#type' => 'textfield',
            '#title' => 'Описание',
        );
        if (isset($result['description']))
            $form['description']['#default_value'] = $result['description'];

        $form['disabled'] = array(
            '#type' => 'select',
            '#title' => 'Активно',
            '#options' => [
                    'false' => 'Да',
                    'true' => 'Нет',
            ],
            '#empty_option' => '- Выбор -',
        );
        if (isset($result['disabled'])){
			$form['disabled']['#default_value'] = 'false';
			if($result['disabled'] == 1)
				$form['disabled']['#default_value'] = 'true';
			if($result['disabled'] == '1')
				$form['disabled']['#default_value'] = 'true';
			if($result['disabled'] == 'true')
				$form['disabled']['#default_value'] = 'true';
		}
		
		$form['sofa_details'] = [
            '#type' => 'fieldset',
            '#title' => 'Диваны',
            '#prefix' => '<div id="sofa_details-fieldset-wrapper">',
            '#suffix' => '</div>',
            '#collapsible' => true,
            //'#collapsed'   => true,
        ];
		
		$sofa = $form_state->get('sofa');
		if ($sofa === NULL) {
			if(isset($result['sofa_details']))
				$sofa = count($result['sofa_details']);
			else
				$sofa = 0;
			$form_state->set('sofa', $sofa);
		}
		for ($j = 0; $j < $sofa; $j++) {
			$form['sofa_details'][$j] = [
				'#type' => 'fieldset',
				'#title' => '- ' . $j,
				'#prefix' => '<div id="sofa_details-fieldset-' . $j . '-wrapper">',
				'#suffix' => '</div>',
				'#collapsible' => true,
				//'#collapsed'   => true,
			];

			$form['sofa_details'][$j]['sofa_id'] = array(
				'#type' => 'textfield',
				'#title' => 'Id',
			);
			if (isset($result['sofa_details'][$j]['sofa_id']))
				$form['sofa_details'][$j]['sofa_id']['#default_value'] = $result['sofa_details'][$j]['sofa_id'];

			$form['sofa_details'][$j]['disabled'] = array(
				'#type' => 'select',
				'#title' => 'Активно',
				'#options' => [
						'false' => 'Да',
						'true' => 'Нет',
				],
				'#empty_option' => '- Выбор -',
			);
			
			if (isset($result['sofa_details'][$j]['disabled'])){
				$form['sofa_details'][$j]['disabled']['#default_value'] = 'false';
				if($result['sofa_details'][$j]['disabled'] == 1)
					$form['sofa_details'][$j]['disabled']['#default_value'] = 'true';
				if($result['sofa_details'][$j]['disabled'] == '1')
					$form['sofa_details'][$j]['disabled']['#default_value'] = 'true';
				if($result['sofa_details'][$j]['disabled'] == 'true')
					$form['sofa_details'][$j]['disabled']['#default_value'] = 'true';
			}
			
		
			$form['sofa_details'][$j]['categories'] = [
				'#type' => 'fieldset',
				'#title' => 'Категории цен',
				'#prefix' => '<div id="sofa_details_'.$j.'_categories-fieldset-wrapper">',
				'#suffix' => '</div>',
				'#collapsible' => true,
				//'#collapsed'   => true,
			];
			
			$cat = $form_state->get('cat'.$j);
			if ($cat === NULL) {
				if(isset($result['sofa_details'][$j]['categories']))
					$cat = count($result['sofa_details'][$j]['categories']);
				else
					$cat = 0;
				$form_state->set('cat'.$j, $cat);
			}
			
			for ($f = 0; $f < $cat; $f++) {
				$form['sofa_details'][$j]['categories'][$f] = [
					'#type' => 'fieldset',
					'#title' => '- ' . $f,
					'#prefix' => '<div id="sofa_details_'.$j.'_categories-' . $f . '-fieldset-wrapper">',
					'#suffix' => '</div>',
					'#collapsible' => true,
					//'#collapsed'   => true,
				];

				$form['sofa_details'][$j]['categories'][$f]['id'] = array(
					'#type' => 'textfield',
					'#title' => 'Id',
				);
				if (isset($result['sofa_details'][$j]['categories'][$f]['id']))
					$form['sofa_details'][$j]['categories'][$f]['id']['#default_value'] = $result['sofa_details'][$j]['categories'][$f]['id'];

				$form['sofa_details'][$j]['categories'][$f]['description'] = array(
					'#type' => 'textfield',
					'#title' => 'Описание',
				);
				if (isset($result['sofa_details'][$j]['categories'][$f]['description']))
					$form['sofa_details'][$j]['categories'][$f]['description']['#default_value'] = $result['sofa_details'][$j]['categories'][$f]['description'];

				$form['sofa_details'][$j]['categories'][$f]['price'] = array(
					'#type' => 'textfield',
					'#title' => 'Цена',
					'#attributes' => array(
						' type' => 'number',
					),
				);
				if (isset($result['sofa_details'][$j]['categories'][$f]['price']))
					$form['sofa_details'][$j]['categories'][$f]['price']['#default_value'] = $result['sofa_details'][$j]['categories'][$f]['price'];

				
				
			}

			$form['sofa_details'][$j]['categories']['action'] = [
				'#type' => 'item',
				'#markup' => $this->t('     '),
			];

			$form['sofa_details'][$j]['categories']['actions'] = [
				'#type' => 'actions',
			];

			$form['sofa_details'][$j]['categories']['actions']['add_sofa_details'] = [
				'#type' => 'submit',
				'#name' => 'add_part',
				'#value' => '',
				'#submit' => ['::addOne'],
				'#ajax' => [
					'callback' => '::changeCallback',
					'wrapper' => 'sofa_details_'.$j.'_categories-fieldset-wrapper',
				],
				'#limit_validation_errors' => array(), 
				'#attributes' => [
					'class' => ['glyphicon glyphicon-plus']
				],
			];

			if ($cat > 0) {
				$form['sofa_details'][$j]['categories']['actions']['remove_sofa_details'] = [
					'#type' => 'submit',
					'#name' => 'remove_part',
					'#value' => '',
					'#submit' => ['::removeOne'],
					'#ajax' => [
						'callback' => '::changeCallback',
						'wrapper' => 'sofa_details_'.$j.'_categories-fieldset-wrapper',
					],
					'#limit_validation_errors' => array(), 
					'#attributes' => [
						'class' => ['glyphicon glyphicon-minus']
					],
				];
			}
			
			
		
			$form['sofa_details'][$j]['collection_details'] = [
				'#type' => 'fieldset',
				'#title' => 'Категории цен',
				'#prefix' => '<div id="sofa_details_'.$j.'_collection_details-fieldset-wrapper">',
				'#suffix' => '</div>',
				'#collapsible' => true,
				//'#collapsed'   => true,
			];
			
			$col = $form_state->get('col'.$j);
			if ($col === NULL) {
				if(isset($result['sofa_details'][$j]['collection_details']))
					$col = count($result['sofa_details'][$j]['collection_details']);
				else
					$col = 0;
				$form_state->set('col'.$j, $col);
			}
			
			for ($f = 0; $f < $col; $f++) {
				$form['sofa_details'][$j]['collection_details'][$f] = [
					'#type' => 'fieldset',
					'#title' => '- ' . $f,
					'#prefix' => '<div id="sofa_details_'.$j.'_collection_details-' . $f . '-fieldset-wrapper">',
					'#suffix' => '</div>',
					'#collapsible' => true,
					//'#collapsed'   => true,
				];

				$form['sofa_details'][$j]['collection_details'][$f]['collection_id'] = array(
					'#type' => 'textfield',
					'#title' => 'collection_id',
				);
				if (isset($result['sofa_details'][$j]['collection_details'][$f]['collection_id']))
					$form['sofa_details'][$j]['collection_details'][$f]['collection_id']['#default_value'] = $result['sofa_details'][$j]['collection_details'][$f]['collection_id'];

				$form['sofa_details'][$j]['collection_details'][$f]['category_id'] = array(
					'#type' => 'textfield',
					'#title' => 'category_id',
				);
				if (isset($result['sofa_details'][$j]['collection_details'][$f]['category_id']))
					$form['sofa_details'][$j]['collection_details'][$f]['category_id']['#default_value'] = $result['sofa_details'][$j]['collection_details'][$f]['category_id'];

				$form['sofa_details'][$j]['collection_details'][$f]['disabled'] = array(
					'#type' => 'select',
					'#title' => 'Активно',
					'#options' => [
							'false' => 'Да',
							'true' => 'Нет',
					],
					'#empty_option' => '- Выбор -',
				);
				
				if (isset($result['sofa_details'][$j]['collection_details'][$f]['disabled'])){
					$form['sofa_details'][$j]['collection_details'][$f]['disabled']['#default_value'] = 'false';
					if($result['sofa_details'][$j]['collection_details'][$f]['disabled'] == 1)
						$form['sofa_details'][$j]['collection_details'][$f]['disabled']['#default_value'] = 'true';
					if($result['sofa_details'][$j]['collection_details'][$f]['disabled'] == '1')
						$form['sofa_details'][$j]['collection_details'][$f]['disabled']['#default_value'] = 'true';
					if($result['sofa_details'][$j]['collection_details'][$f]['disabled'] == 'true')
						$form['sofa_details'][$j]['collection_details'][$f]['disabled']['#default_value'] = 'true';
				}	
			}

			$form['sofa_details'][$j]['collection_details']['action'] = [
				'#type' => 'item',
				'#markup' => $this->t('     '),
			];

			$form['sofa_details'][$j]['collection_details']['actions'] = [
				'#type' => 'actions',
			];

			$form['sofa_details'][$j]['collection_details']['actions']['add_sofa_details'] = [
				'#type' => 'submit',
				'#name' => 'add_part',
				'#value' => '',
				'#submit' => ['::addOne'],
				'#ajax' => [
					'callback' => '::changeCallback',
					'wrapper' => 'sofa_details_'.$j.'_collection_details-fieldset-wrapper',
				],
				'#limit_validation_errors' => array(), 
				'#attributes' => [
					'class' => ['glyphicon glyphicon-plus']
				],
			];

			if ($col > 0) {
				$form['sofa_details'][$j]['collection_details']['actions']['remove_sofa_details'] = [
					'#type' => 'submit',
					'#name' => 'remove_part',
					'#value' => '',
					'#submit' => ['::removeOne'],
					'#ajax' => [
						'callback' => '::changeCallback',
						'wrapper' => 'sofa_details_'.$j.'_collection_details-fieldset-wrapper',
					],
					'#limit_validation_errors' => array(), 
					'#attributes' => [
						'class' => ['glyphicon glyphicon-minus']
					],
				];
			}
			
			
		
			$form['sofa_details'][$j]['fabric_details'] = [
				'#type' => 'fieldset',
				'#title' => 'Категории цен',
				'#prefix' => '<div id="sofa_details_'.$j.'_fabric_details-fieldset-wrapper">',
				'#suffix' => '</div>',
				'#collapsible' => true,
				//'#collapsed'   => true,
			];
			
			$fab = $form_state->get('fab'.$j);
			if ($fab === NULL) {
				if(isset($result['sofa_details'][$j]['fabric_details']))
					$fab = count($result['sofa_details'][$j]['fabric_details']);
				else
					$fab = 0;
				$form_state->set('fab'.$j, $fab);
			}
			
			for ($f = 0; $f < $fab; $f++) {
				$form['sofa_details'][$j]['fabric_details'][$f] = [
					'#type' => 'fieldset',
					'#title' => '- ' . $f,
					'#prefix' => '<div id="sofa_details_'.$j.'_fabric_details-' . $f . '-fieldset-wrapper">',
					'#suffix' => '</div>',
					'#collapsible' => true,
					//'#collapsed'   => true,
				];

				$form['sofa_details'][$j]['fabric_details'][$f]['fabric_id'] = array(
					'#type' => 'textfield',
					'#title' => 'fabric_id',
				);
				if (isset($result['sofa_details'][$j]['fabric_details'][$f]['fabric_id']))
					$form['sofa_details'][$j]['fabric_details'][$f]['fabric_id']['#default_value'] = $result['sofa_details'][$j]['fabric_details'][$f]['fabric_id'];

				$form['sofa_details'][$j]['fabric_details'][$f]['disabled'] = array(
					'#type' => 'select',
					'#title' => 'Активно',
					'#options' => [
							'false' => 'Да',
							'true' => 'Нет',
					],
					'#empty_option' => '- Выбор -',
				);
				
				if (isset($result['sofa_details'][$j]['fabric_details'][$f]['disabled'])){
					$form['sofa_details'][$j]['fabric_details'][$f]['disabled']['#default_value'] = 'false';
					if($result['sofa_details'][$j]['fabric_details'][$f]['disabled'] == 1)
						$form['sofa_details'][$j]['fabric_details'][$f]['disabled']['#default_value'] = 'true';
					if($result['sofa_details'][$j]['fabric_details'][$f]['disabled'] == '1')
						$form['sofa_details'][$j]['fabric_details'][$f]['disabled']['#default_value'] = 'true';
					if($result['sofa_details'][$j]['fabric_details'][$f]['disabled'] == 'true')
						$form['sofa_details'][$j]['fabric_details'][$f]['disabled']['#default_value'] = 'true';
				}	
			}

			$form['sofa_details'][$j]['fabric_details']['action'] = [
				'#type' => 'item',
				'#markup' => $this->t('     '),
			];

			$form['sofa_details'][$j]['fabric_details']['actions'] = [
				'#type' => 'actions',
			];

			$form['sofa_details'][$j]['fabric_details']['actions']['add_sofa_details'] = [
				'#type' => 'submit',
				'#name' => 'add_part',
				'#value' => '',
				'#submit' => ['::addOne'],
				'#ajax' => [
					'callback' => '::changeCallback',
					'wrapper' => 'sofa_details_'.$j.'_fabric_details-fieldset-wrapper',
				],
				'#limit_validation_errors' => array(), 
				'#attributes' => [
					'class' => ['glyphicon glyphicon-plus']
				],
			];

			if ($fab > 0) {
				$form['sofa_details'][$j]['fabric_details']['actions']['remove_sofa_details'] = [
					'#type' => 'submit',
					'#name' => 'remove_part',
					'#value' => '',
					'#submit' => ['::removeOne'],
					'#ajax' => [
						'callback' => '::changeCallback',
						'wrapper' => 'sofa_details_'.$j.'_fabric_details-fieldset-wrapper',
					],
					'#limit_validation_errors' => array(), 
					'#attributes' => [
						'class' => ['glyphicon glyphicon-minus']
					],
				];
			}
		}

		

		$form['sofa_details']['action'] = [
			'#type' => 'item',
			'#markup' => $this->t('     '),
		];

		$form['sofa_details']['actions'] = [
			'#type' => 'actions',
		];

		$form['sofa_details']['actions']['add_sofa_details'] = [
			'#type' => 'submit',
			'#name' => 'add_part',
			'#value' => '',
			'#submit' => ['::addOne'],
			'#ajax' => [
				'callback' => '::changeCallback',
				'wrapper' => 'sofa_details-fieldset-wrapper',
			],
			'#limit_validation_errors' => array(), 
			'#attributes' => [
				'class' => ['glyphicon glyphicon-plus']
			],
		];

		if ($sofa > 0) {
			$form['sofa_details']['actions']['remove_sofa_details'] = [
				'#type' => 'submit',
				'#name' => 'remove_part',
				'#value' => '',
				'#submit' => ['::removeOne'],
				'#ajax' => [
					'callback' => '::changeCallback',
					'wrapper' => 'sofa_details-fieldset-wrapper',
				],
				'#limit_validation_errors' => array(), 
				'#attributes' => [
					'class' => ['glyphicon glyphicon-minus']
				],
			];
		}

	
	

        $form['submit'] = array(
            '#type' => 'submit',
            '#name' => 'submit',
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
        if(($trigger['#parents'][0] === 'sofa_details') && ($trigger['#parents'][2] === 'categories'))
            return $form['sofa_details'][$trigger['#parents'][1]]['categories'];
        elseif(($trigger['#parents'][0] === 'sofa_details') && ($trigger['#parents'][2] === 'collection_details'))
            return $form['sofa_details'][$trigger['#parents'][1]]['collection_details'];
        elseif(($trigger['#parents'][0] === 'sofa_details') && ($trigger['#parents'][2] === 'fabric_details'))
            return $form['sofa_details'][$trigger['#parents'][1]]['fabric_details'];
        elseif($trigger['#parents'][0] === 'sofa_details')
            return $form['sofa_details'];
        else
            return $form;
    }

    public function typeAjax(FormStateInterface $form_state){
        $trigger = $form_state->getTriggeringElement();
        drupal_set_message(['#type' => 'item', '#markup' => print_r($trigger, true),]);
        if($trigger['#ajax']['wrapper'] === 'sofa_details-fieldset-wrapper'){
            $num_res = $form_state->get('sofa');
            $num_txt = 'sofa';
        }
        else if(strpos( $trigger['#ajax']['wrapper'], 'categories-fieldset-wrapper' ) !== false){
            $num_res = $form_state->get('cat'.$trigger['#parents'][1]);
            $num_txt = 'cat'.$trigger['#parents'][1];
        }
        else if(strpos( $trigger['#ajax']['wrapper'], 'collection_details-fieldset-wrapper' ) !== false){
            $num_res = $form_state->get('col'.$trigger['#parents'][1]);
            $num_txt = 'col'.$trigger['#parents'][1];
        }
        else if(strpos( $trigger['#ajax']['wrapper'], 'fabric_details-fieldset-wrapper' ) !== false){
            $num_res = $form_state->get('fab'.$trigger['#parents'][1]);
            $num_txt = 'fab'.$trigger['#parents'][1];
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
    function validateForm(array &$form, FormStateInterface $form_state){
    }

    /**
     * {@inheritdoc}
     */
    // Вместо hook_form_submit.
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $setting = $form_state->getValues();
		$id = $setting['id'];
        $trigger = $form_state->getTriggeringElement();
		drupal_set_message(['#type' => 'item', '#markup' => print_r($trigger, true),]);

		$result = [
			'id' => $setting['id'],
			'name' => $setting['name'],
			'description' => $setting['description'],
			'productType' => $setting['productType'],
			'fabricType' => $setting['fabricType'],
			'manufacturerIds' => [],
			'similarFabricIds' => [],
			'companionFabricIds' => [],
			'tags' => [],
			'colors' => [],
			'producingCountry' => $setting['producingCountry'],
			'designTypeName' => $setting['designTypeName'],
			'rapportSizeDescription' => [
				'width' => $setting['rapportSizeDescription']['width'],
				'height' => $setting['rapportSizeDescription']['height'],
			],
			'fabricRollWidth' => [
				'width' => $setting['fabricRollWidth']['width'],
				'error' => $setting['fabricRollWidth']['error'],
			],
			'materialAbrasionResistance' => [
				'count' => $setting['materialAbrasionResistance']['count'],
				'sign' => $setting['materialAbrasionResistance']['sign'],
			],
			'materialDensity' => [
				'gram' => $setting['materialDensity']['gram'],
				'error' => $setting['materialDensity']['error'],
			],
			'materialDirection' => $setting['materialDirection'],
			'materials' => [],
			'careList' => [],
			'detailsList' => [],
			'storeUnits' => $setting['storeUnits'],
			'storePrice' => $setting['storePrice'],
			'storeMinimumOrder' => $setting['storeMinimumOrder'],
			'textureConfig' => [
				'glossiness' => $setting['textureConfig']['glossiness'],
				'size' => [
					'x' => $setting['textureConfig']['size']['x'],
					'y' => $setting['textureConfig']['size']['y'],
					'z' => $setting['textureConfig']['size']['z'],
				],
			],
		];

		$manuf =  $form_state->get('manuf');
		$array_manuf = array();
		for ($j = 0; $j < $manuf; $j++) {
			$array_manuf[] = $setting['manufacturerIds'][$j];
		}
		$result['manufacturerIds'] = $array_manuf;

		$similar =  $form_state->get('similar');
		$array_similar = array();
		for ($j = 0; $j < $similar; $j++) {
			$array_similar[] = $setting['similarFabricIds'][$j];
		}
		$result['similarFabricIds'] = $array_similar;

		$comp =  $form_state->get('comp');
		$array_comp = array();
		for ($j = 0; $j < $comp; $j++) {
			$array_comp[] = $setting['companionFabricIds'][$j];
		}
		$result['companionFabricIds'] = $array_comp;

		$tags =  $form_state->get('tags');
		$array_tags = array();
		for ($j = 0; $j < $tags; $j++) {
			$array_tags[] = $setting['tags'][$j];
		}
		$result['tags'] = $array_tags;

		$colors =  $form_state->get('colors');
		$array_colors = array();
		for ($j = 0; $j < $colors; $j++) {
			$array_colors[] = $setting['colors'][$j];
		}
		$result['colors'] = $array_colors;

		$material =  $form_state->get('material');
		$array_material = array();
		for ($j = 0; $j < $material; $j++) {
			$array_material[] = [
				'name' => $setting['materials'][$j]['name'],
				'percent' => $setting['materials'][$j]['percent']
			];
		}
		$result['materials'] = $array_material;

		$care =  $form_state->get('care');
		$array_care = array();
		for ($j = 0; $j < $care; $j++) {
			$array_care[] = $setting['careList'][$j];
		}
		$result['careList'] = $array_care;

		$details =  $form_state->get('details');
		$array_details = array();
		for ($j = 0; $j < $details; $j++) {
			$array_details[] = $setting['detailsList'][$j];
		}
		$result['detailsList'] = $array_details;

		
		$method  = 'Fabrics';
		$result = SofaApiLogic::send($method, $result, 'PUT', $setting['id']);
		if($result === []){
			drupal_set_message('Успешно');
			//drupal_set_message(['#type' => 'item', '#markup' => print_r($result, true),]);
		} else {
			drupal_set_message('Не обновлено', 'error');
		}
	
    }

}