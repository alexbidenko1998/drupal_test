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

class FabricEdit extends FormBase {

    /**
     * {@inheritdoc}.
     */
    // Метод для котороый возвращает ид формы.
    public function getFormId() {
        return 'FabricEdit_form';
    }

    /**
     * {@inheritdoc}.
     */
    // Вместо hook_form.
    public function buildForm(array $form, FormStateInterface $form_state, $fabricId = '')
    {

		$method  = 'Fabrics';
		$result = SofaApiLogic::send($method, $result, 'GET', $fabricId);

        $form['#tree'] = TRUE;
        $form['#prefix'] = '<div id="all-fieldset-wrapper">';
        $form['#suffix'] = '</div>';
		

        $form['id'] = array(
            '#type' => 'hidden',
            '#default_value' => $fabricId,
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
		
		if(isset($result['textures']['main_texture']['url'])){
			$uri = SofaApiLogic::getImageUrl().$result['textures']['main_texture']['url'];
			$avatar_url1 = "<img src=\"{$uri}\"   width=\"25%\" height=\"25%\">";
		
			$form['label_main_texture'] = array(
				'#type' => 'label',
				'#title' => 'Основная текстура:',
			);
		
			$form['main_texture'] = array(
				'#type' => 'item',
				'#markup' => $avatar_url1,
			);
		
			$form['main_texture_delete'] = array(
				'#type' => 'submit',
				'#name' => 'main_texture_delete',
				'#value' => 'Удалить',
				'#attributes' => [
					'class' => ['btn-danger']
				]
			);
			$form['main_texture_delete']['#limit_validation_errors'] = array();

			$form['main_texture_space'] = [
				'#type' => 'item',
				'#markup' => $this->t('     '),
			];			
		}
		
		if(isset($result['textures']['normal_map']['url'])){
			$uri = SofaApiLogic::getImageUrl().$result['textures']['normal_map']['url'];
			$avatar_url2 = "<img src=\"{$uri}\"   width=\"25%\" height=\"25%\">";
		
			$form['normal_map_label'] = array(
				'#type' => 'label',
				'#title' => 'Карта нормалей:',
			);
			
			$form['normal_map'] = array(
				'#type' => 'item',
				'#markup' => $avatar_url2,
			);
			
			$form['normal_map_delete'] = array(
				'#type' => 'submit',
				'#name' => 'normal_map_delete',
				'#value' => 'Удалить',
				'#attributes' => [
					'class' => ['btn-danger']
				]
			);
			$form['normal_map_delete']['#limit_validation_errors'] = array();

			$form['normal_map_space'] = [
				'#type' => 'item',
				'#markup' => $this->t('     '),
			];
		}
		
		if(isset($result['textures']['metallic_gloss_map']['url'])){
			$uri = SofaApiLogic::getImageUrl().$result['textures']['metallic_gloss_map']['url'];
			$avatar_url3 = "<img src=\"{$uri}\"   width=\"25%\" height=\"25%\">";
			
			$form['metallic_gloss_map_label'] = array(
				'#type' => 'label',
				'#title' => 'Карта металлических отражений:',
			);
			
			$form['metallic_gloss_map'] = array(
				'#type' => 'item',
				'#markup' => $avatar_url3,
			);
			
			$form['metallic_gloss_map_delete'] = array(
				'#type' => 'submit',
				'#name' => 'metallic_gloss_map_delete',
				'#value' => 'Удалить',
				'#attributes' => [
					'class' => ['btn-danger']
				]
			);
			$form['metallic_gloss_map_delete']['#limit_validation_errors'] = array();

			$form['metallic_gloss_map_space'] = [
				'#type' => 'item',
				'#markup' => $this->t('     '),
			];
		}
		

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

        $form['productType'] = array(
            '#type' => 'textfield',
            '#title' => 'Тип продукта',
        );
        if (isset($result['productType']))
            $form['productType']['#default_value'] = $result['productType'];

        $form['fabricType'] = array(
            '#type' => 'textfield',
            '#title' => 'Тип такни',
        );
        if (isset($result['fabricType']))
            $form['fabricType']['#default_value'] = $result['fabricType'];
		
		$form['manufacturerIds'] = [
            '#type' => 'fieldset',
            '#title' => 'Идентификаторы производителя',
            '#prefix' => '<div id="manufacturerIds-fieldset-wrapper">',
            '#suffix' => '</div>',
            '#collapsible' => true,
            //'#collapsed'   => true,
        ];
		
		$manuf = $form_state->get('manuf');
		if ($manuf === NULL) {
			if(isset($result['manufacturerIds']))
				$manuf = count($result['manufacturerIds']);
			else
				$manuf = 0;
			$form_state->set('manuf', $manuf);
		}
		
		for ($j = 0; $j < $manuf; $j++) {
			$form['manufacturerIds'][$j] = array(
				'#type' => 'textfield',
			);
			if (isset($result['manufacturerIds'][$j]))
				$form['manufacturerIds'][$j]['#default_value'] = $result['manufacturerIds'][$j];
		}

		$form['manufacturerIds']['action'] = [
			'#type' => 'item',
			'#markup' => $this->t('     '),
		];

		$form['manufacturerIds']['actions'] = [
			'#type' => 'actions',
		];

		$form['manufacturerIds']['actions']['add_manuf'] = [
			'#type' => 'submit',
			'#name' => 'add_manuf',
			'#value' => '',
			'#submit' => ['::addOne'],
			'#ajax' => [
				'callback' => '::changeCallback',
				'wrapper' => 'manufacturerIds-fieldset-wrapper',
			],
			'#limit_validation_errors' => array(), 
			'#attributes' => [
				'class' => ['glyphicon glyphicon-plus']
			],
		];

		if ($manuf > 0) {
			$form['manufacturerIds']['actions']['remove_manuf'] = [
				'#type' => 'submit',
				'#name' => 'remove_manuf',
				'#value' => '',
				'#submit' => ['::removeOne'],
				'#ajax' => [
					'callback' => '::changeCallback',
					'wrapper' => 'manufacturerIds-fieldset-wrapper',
				],
				'#limit_validation_errors' => array(), 
				'#attributes' => [
					'class' => ['glyphicon glyphicon-minus']
				],
			];
		}
		
		$form['similarFabricIds'] = [
            '#type' => 'fieldset',
            '#title' => 'Похожие идентификаторы ткани',
            '#prefix' => '<div id="similarFabricIds-fieldset-wrapper">',
            '#suffix' => '</div>',
            '#collapsible' => true,
            //'#collapsed'   => true,
        ];
		
		$similar = $form_state->get('similar');
		if ($similar === NULL) {
			if(isset($result['similarFabricIds']))
				$similar = count($result['similarFabricIds']);
			else
				$similar = 0;
			$form_state->set('similar', $similar);
		}
		
		for ($j = 0; $j < $similar; $j++) {
			$form['similarFabricIds'][$j] = array(
				'#type' => 'textfield',
			);
			if (isset($result['similarFabricIds'][$j]))
				$form['similarFabricIds'][$j]['#default_value'] = $result['similarFabricIds'][$j];
		}

		$form['similarFabricIds']['action'] = [
			'#type' => 'item',
			'#markup' => $this->t('     '),
		];

		$form['similarFabricIds']['actions'] = [
			'#type' => 'actions',
		];

		$form['similarFabricIds']['actions']['add_similar'] = [
			'#type' => 'submit',
			'#name' => 'add_similar',
			'#value' => '',
			'#submit' => ['::addOne'],
			'#ajax' => [
				'callback' => '::changeCallback',
				'wrapper' => 'similarFabricIds-fieldset-wrapper',
			],
			'#limit_validation_errors' => array(), 
			'#attributes' => [
				'class' => ['glyphicon glyphicon-plus']
			],
		];

		if ($similar > 0) {
			$form['similarFabricIds']['actions']['remove_similar'] = [
				'#type' => 'submit',
				'#name' => 'remove_similar',
				'#value' => '',
				'#submit' => ['::removeOne'],
				'#ajax' => [
					'callback' => '::changeCallback',
					'wrapper' => 'similarFabricIds-fieldset-wrapper',
				],
				'#limit_validation_errors' => array(), 
				'#attributes' => [
					'class' => ['glyphicon glyphicon-minus']
				],
			];
		}
		
		$form['companionFabricIds'] = [
            '#type' => 'fieldset',
            '#title' => 'Сопутсвующие ткани',
            '#prefix' => '<div id="companionFabricIds-fieldset-wrapper">',
            '#suffix' => '</div>',
            '#collapsible' => true,
            //'#collapsed'   => true,
        ];
		
		$comp = $form_state->get('comp');
		if ($comp === NULL) {
			if(isset($result['companionFabricIds']))
				$comp = count($result['companionFabricIds']);
			else
				$comp = 0;
			$form_state->set('comp', $comp);
		}
		for ($j = 0; $j < $comp; $j++) {
			$form['companionFabricIds'][$j] = array(
				'#type' => 'textfield',
			);
			if (isset($result['companionFabricIds'][$j]))
				$form['companionFabricIds'][$j]['#default_value'] = $result['companionFabricIds'][$j];
		}

		$form['companionFabricIds']['action'] = [
			'#type' => 'item',
			'#markup' => $this->t('     '),
		];

		$form['companionFabricIds']['actions'] = [
			'#type' => 'actions',
		];

		$form['companionFabricIds']['actions']['add_comp'] = [
			'#type' => 'submit',
			'#name' => 'add_comp',
			'#value' => '',
			'#submit' => ['::addOne'],
			'#ajax' => [
				'callback' => '::changeCallback',
				'wrapper' => 'companionFabricIds-fieldset-wrapper',
			],
			'#limit_validation_errors' => array(), 
			'#attributes' => [
				'class' => ['glyphicon glyphicon-plus']
			],
		];

		if ($comp > 0) {
			$form['companionFabricIds']['actions']['remove_comp'] = [
				'#type' => 'submit',
				'#name' => 'remove_comp',
				'#value' => '',
				'#submit' => ['::removeOne'],
				'#ajax' => [
					'callback' => '::changeCallback',
					'wrapper' => 'companionFabricIds-fieldset-wrapper',
				],
				'#limit_validation_errors' => array(), 
				'#attributes' => [
					'class' => ['glyphicon glyphicon-minus']
				],
			];
		}
		
		$form['tags'] = [
            '#type' => 'fieldset',
            '#title' => 'Тэги',
            '#prefix' => '<div id="tags-fieldset-wrapper">',
            '#suffix' => '</div>',
            '#collapsible' => true,
            //'#collapsed'   => true,
        ];
		
		$tags = $form_state->get('tags');
		if ($tags === NULL) {
			if(isset($result['tags']))
				$tags = count($result['tags']);
			else
				$tags = 0;
			$form_state->set('tags', $tags);
		}
		
		for ($j = 0; $j < $tags; $j++) {
			$form['tags'][$j] = array(
				'#type' => 'textfield',
			);
			if (isset($result['tags'][$j]))
				$form['tags'][$j]['#default_value'] = $result['tags'][$j];
		}

		$form['tags']['action'] = [
			'#type' => 'item',
			'#markup' => $this->t('     '),
		];

		$form['tags']['actions'] = [
			'#type' => 'actions',
		];

		$form['tags']['actions']['add_tags'] = [
			'#type' => 'submit',
			'#name' => 'add_tags',
			'#value' => '',
			'#submit' => ['::addOne'],
			'#ajax' => [
				'callback' => '::changeCallback',
				'wrapper' => 'tags-fieldset-wrapper',
			],
			'#limit_validation_errors' => array(), 
			'#attributes' => [
				'class' => ['glyphicon glyphicon-plus']
			],
		];

		if ($tags > 0) {
			$form['tags']['actions']['remove_tags'] = [
				'#type' => 'submit',
				'#name' => 'remove_tags',
				'#value' => '',
				'#submit' => ['::removeOne'],
				'#ajax' => [
					'callback' => '::changeCallback',
					'wrapper' => 'tags-fieldset-wrapper',
				],
				'#limit_validation_errors' => array(), 
				'#attributes' => [
					'class' => ['glyphicon glyphicon-minus']
				],
			];
		}
		
		$form['colors'] = [
            '#type' => 'fieldset',
            '#title' => 'Цвета',
            '#prefix' => '<div id="colors-fieldset-wrapper">',
            '#suffix' => '</div>',
            '#collapsible' => true,
            //'#collapsed'   => true,
        ];
		
		$colors = $form_state->get('colors');
		if ($colors === NULL) {
			if(isset($result['colors']))
				$colors = count($result['colors']);
			else
				$colors = 0;
			$form_state->set('colors', $colors);
		}
		
		for ($j = 0; $j < $colors; $j++) {
			$form['colors'][$j] = array(
				'#type' => 'textfield',
			);
			if (isset($result['colors'][$j]))
				$form['colors'][$j]['#default_value'] = $result['colors'][$j];
		}

		$form['colors']['action'] = [
			'#type' => 'item',
			'#markup' => $this->t('     '),
		];

		$form['colors']['actions'] = [
			'#type' => 'actions',
		];

		$form['colors']['actions']['add_colors'] = [
			'#type' => 'submit',
			'#name' => 'add_colors',
			'#value' => '',
			'#submit' => ['::addOne'],
			'#ajax' => [
				'callback' => '::changeCallback',
				'wrapper' => 'colors-fieldset-wrapper',
			],
			'#limit_validation_errors' => array(), 
			'#attributes' => [
				'class' => ['glyphicon glyphicon-plus']
			],
		];

		if ($colors > 0) {
			$form['colors']['actions']['remove_colors'] = [
				'#type' => 'submit',
				'#name' => 'remove_colors',
				'#value' => '',
				'#submit' => ['::removeOne'],
				'#ajax' => [
					'callback' => '::changeCallback',
					'wrapper' => 'colors-fieldset-wrapper',
				],
				'#limit_validation_errors' => array(), 
				'#attributes' => [
					'class' => ['glyphicon glyphicon-minus']
				],
			];
		}

        $form['producingCountry'] = array(
            '#type' => 'textfield',
            '#title' => 'Страна производитель',
        );
		if (isset($result['producingCountry']))
			$form['producingCountry']['#default_value'] = $result['producingCountry'];

        $form['designTypeName'] = array(
            '#type' => 'textfield',
            '#title' => 'Название типа конструкции',
        );
		if (isset($result['designTypeName']))
			$form['designTypeName']['#default_value'] = $result['designTypeName'];
		
		$form['rapportSizeDescription'] = [
			'#type' => 'fieldset',
			'#title' => 'Описание размера рапорта',
			'#prefix' => '<div id="rapportSizeDescription-fieldset-wrapper">',
			'#suffix' => '</div>',
			'#collapsible' => true,
			//'#collapsed'   => true,
		];

		$form['rapportSizeDescription']['width'] = array(
			'#type' => 'textfield',
			'#title' => 'Ширина',
			'#attributes' => array(
				' type' => 'number',
			),
		);
		if (isset($result['rapportSizeDescription']['width']))
			$form['rapportSizeDescription']['width']['#default_value'] = $result['rapportSizeDescription']['width'];

		$form['rapportSizeDescription']['height'] = array(
			'#type' => 'textfield',
			'#title' => 'Высота',
			'#attributes' => array(
				' type' => 'number',
			),
		);
		if (isset($result['rapportSizeDescription']['height']))
			$form['rapportSizeDescription']['height']['#default_value'] = $result['rapportSizeDescription']['height'];
		
		$form['fabricRollWidth'] = [
			'#type' => 'fieldset',
			'#title' => 'Ширина рулона ткани',
			'#prefix' => '<div id="fabricRollWidth-fieldset-wrapper">',
			'#suffix' => '</div>',
			'#collapsible' => true,
			//'#collapsed'   => true,
		];

		$form['fabricRollWidth']['width'] = array(
			'#type' => 'textfield',
			'#title' => 'Ширина',
			'#attributes' => array(
				' type' => 'number',
			),
		);
		if (isset($result['fabricRollWidth']['width']))
			$form['fabricRollWidth']['width']['#default_value'] = $result['fabricRollWidth']['width'];

		$form['fabricRollWidth']['error'] = array(
			'#type' => 'textfield',
			'#title' => 'Погрешность',
			'#attributes' => array(
				' type' => 'number',
			),
		);
		if (isset($result['fabricRollWidth']['error']))
			$form['fabricRollWidth']['error']['#default_value'] = $result['fabricRollWidth']['error'];
		
		$form['materialAbrasionResistance'] = [
			'#type' => 'fieldset',
			'#title' => 'Сопротивление истиранию материала',
			'#prefix' => '<div id="materialAbrasionResistance-fieldset-wrapper">',
			'#suffix' => '</div>',
			'#collapsible' => true,
			//'#collapsed'   => true,
		];

		$form['materialAbrasionResistance']['count'] = array(
			'#type' => 'textfield',
			'#title' => 'Количество',
			'#attributes' => array(
				' type' => 'number',
			),
		);
		if (isset($result['materialAbrasionResistance']['count']))
			$form['materialAbrasionResistance']['count']['#default_value'] = $result['materialAbrasionResistance']['count'];

		$form['materialAbrasionResistance']['sign'] = array(
			'#type' => 'textfield',
			'#title' => 'Знак',
			'#attributes' => array(
				' type' => 'number',
			),
		);
		if (isset($result['materialAbrasionResistance']['sign']))
			$form['materialAbrasionResistance']['sign']['#default_value'] = $result['materialAbrasionResistance']['sign'];
		
		$form['materialDensity'] = [
			'#type' => 'fieldset',
			'#title' => 'Плотность материала',
			'#prefix' => '<div id="materialDensity-fieldset-wrapper">',
			'#suffix' => '</div>',
			'#collapsible' => true,
			//'#collapsed'   => true,
		];

		$form['materialDensity']['gram'] = array(
			'#type' => 'textfield',
			'#title' => 'Грамм на квадратный метр',
			'#attributes' => array(
				' type' => 'number',
			),
		);
		if (isset($result['materialDensity']['gram']))
			$form['materialDensity']['gram']['#default_value'] = $result['materialDensity']['gram'];

		$form['materialDensity']['error'] = array(
			'#type' => 'textfield',
			'#title' => 'Погрешность',
			'#attributes' => array(
				' type' => 'number',
			),
		);
		if (isset($result['materialDensity']['error']))
			$form['materialDensity']['error']['#default_value'] = $result['materialDensity']['error'];

        $form['materialDirection'] = array(
            '#type' => 'select',
            '#title' => 'materialDirection',
            '#options' => [
                    'r0' => 'r0',
                    'r90' => 'r90',
                    'r180' => 'r180',
                    'r270' => 'r270',
            ],
            '#empty_option' => '- Выбор -',
        );
		if (isset($result['materialDirection']))
			$form['materialDirection']['#default_value'] = $result['materialDirection'];
		
		$form['materials'] = [
            '#type' => 'fieldset',
            '#title' => 'Список материалов',
            '#prefix' => '<div id="materials-fieldset-wrapper">',
            '#suffix' => '</div>',
            '#collapsible' => true,
            //'#collapsed'   => true,
        ];
		
		$material = $form_state->get('material');
		if ($material === NULL) {
			if(isset($result['materialsList']))
				$material = count($result['materialsList']);
			else
				$material = 0;
			$form_state->set('material', $material);
		}
		
		
		for ($j = 0; $j < $material; $j++) {
			$form['materials'][$j] = [
				'#type' => 'fieldset',
				'#title' => '- ' . $j,
				'#prefix' => '<div id="materials-fieldset-' . $j . '-wrapper">',
				'#suffix' => '</div>',
				'#collapsible' => true,
				//'#collapsed'   => true,
			];

			$form['materials'][$j]['name'] = array(
				'#type' => 'textfield',
				'#title' => 'Название',
			);
			if (isset($result['materialsList'][$j]['name']))
				$form['materialsList'][$j]['name']['#default_value'] = $result['materialsList'][$j]['name'];

			$form['materials'][$j]['percent'] = array(
				'#type' => 'textfield',
				'#title' => 'Процентный состав',
			);
			if (isset($result['materialsList'][$j]['percent']))
				$form['materialsList'][$j]['percent']['#default_value'] = $result['materials'][$j]['percent'];
		}

		$form['materials']['action'] = [
			'#type' => 'item',
			'#markup' => $this->t('     '),
		];

		$form['materials']['actions'] = [
			'#type' => 'actions',
		];

		$form['materials']['actions']['add_material'] = [
			'#type' => 'submit',
			'#name' => 'add_material',
			'#value' => '',
			'#submit' => ['::addOne'],
			'#ajax' => [
				'callback' => '::changeCallback',
				'wrapper' => 'materials-fieldset-wrapper',
			],
			'#limit_validation_errors' => array(), 
			'#attributes' => [
				'class' => ['glyphicon glyphicon-plus']
			],
		];

		if ($material > 0) {
			$form['materials']['actions']['remove_material'] = [
				'#type' => 'submit',
				'#name' => 'remove_material',
				'#value' => '',
				'#submit' => ['::removeOne'],
				'#ajax' => [
					'callback' => '::changeCallback',
					'wrapper' => 'materials-fieldset-wrapper',
				],
				'#limit_validation_errors' => array(), 
				'#attributes' => [
					'class' => ['glyphicon glyphicon-minus']
				],
			];
		}
		
		$form['careList'] = [
            '#type' => 'fieldset',
            '#title' => 'care_list',
            '#prefix' => '<div id="careList-fieldset-wrapper">',
            '#suffix' => '</div>',
            '#collapsible' => true,
            //'#collapsed'   => true,
        ];
		
		
		$care = $form_state->get('care');
		if ($care === NULL) {
			if(isset($result['careList']))
				$care = count($result['careList']);
			else
				$care = 0;
			$form_state->set('care', $care);
		}
		
		for ($j = 0; $j < $care; $j++) {
			$form['careList'][$j] = array(
				'#type' => 'textfield',
			);
			if (isset($result['careList'][$j]))
				$form['careList'][$j]['#default_value'] = $result['careList'][$j];
		}

		$form['careList']['action'] = [
			'#type' => 'item',
			'#markup' => $this->t('     '),
		];

		$form['careList']['actions'] = [
			'#type' => 'actions',
		];

		$form['careList']['actions']['add_care'] = [
			'#type' => 'submit',
			'#name' => 'add_care',
			'#value' => '',
			'#submit' => ['::addOne'],
			'#ajax' => [
				'callback' => '::changeCallback',
				'wrapper' => 'careList-fieldset-wrapper',
			],
			'#limit_validation_errors' => array(), 
			'#attributes' => [
				'class' => ['glyphicon glyphicon-plus']
			],
		];

		if ($care > 0) {
			$form['careList']['actions']['remove_care'] = [
				'#type' => 'submit',
				'#name' => 'remove_care',
				'#value' => '',
				'#submit' => ['::removeOne'],
				'#ajax' => [
					'callback' => '::changeCallback',
					'wrapper' => 'careList-fieldset-wrapper',
				],
				'#limit_validation_errors' => array(), 
				'#attributes' => [
					'class' => ['glyphicon glyphicon-minus']
				],
			];
		}
		
		$form['detailsList'] = [
            '#type' => 'fieldset',
            '#title' => 'Cписок деталей',
            '#prefix' => '<div id="detailsList-fieldset-wrapper">',
            '#suffix' => '</div>',
            '#collapsible' => true,
            //'#collapsed'   => true,
        ];
		
		
		$details = $form_state->get('details');
		if ($details === NULL) {
			if(isset($result['detailsList']))
				$details = count($result['detailsList']);
			else
				$details = 0;
			$form_state->set('details', $details);
		}
		
		for ($j = 0; $j < $details; $j++) {
			$form['detailsList'][$j] = array(
				'#type' => 'textfield',
			);
			if (isset($result['detailsList'][$j]))
				$form['detailsList'][$j]['#default_value'] = $result['detailsList'][$j];
		}

		$form['detailsList']['action'] = [
			'#type' => 'item',
			'#markup' => $this->t('     '),
		];

		$form['detailsList']['actions'] = [
			'#type' => 'actions',
		];

		$form['detailsList']['actions']['add_details'] = [
			'#type' => 'submit',
			'#name' => 'add_details',
			'#value' => '',
			'#submit' => ['::addOne'],
			'#ajax' => [
				'callback' => '::changeCallback',
				'wrapper' => 'detailsList-fieldset-wrapper',
			],
			'#limit_validation_errors' => array(), 
			'#attributes' => [
				'class' => ['glyphicon glyphicon-plus']
			],
		];

		if ($details > 0) {
			$form['detailsList']['actions']['remove_details'] = [
				'#type' => 'submit',
				'#name' => 'remove_details',
				'#value' => '',
				'#submit' => ['::removeOne'],
				'#ajax' => [
					'callback' => '::changeCallback',
					'wrapper' => 'detailsList-fieldset-wrapper',
				],
				'#limit_validation_errors' => array(), 
				'#attributes' => [
					'class' => ['glyphicon glyphicon-minus']
				],
			];
		}
		
        $form['storeUnits'] = array(
            '#type' => 'textfield',
            '#title' => 'store_units',
        );
		if (isset($result['storeUnits']))
			$form['storeUnits']['#default_value'] = $result['storeUnits'];

        $form['storePrice'] = array(
            '#type' => 'textfield',
            '#title' => 'Магазиная цена',
            '#attributes' => array(
                ' type' => 'number',
            ),
        );
		if (isset($result['storePrice']))
			$form['storePrice']['#default_value'] = $result['storePrice'];

        $form['storeMinimumOrder'] = array(
            '#type' => 'textfield',
            '#title' => 'Минимальный заказ из магазина',
            '#attributes' => array(
                ' type' => 'number',
            ),
        );
		if (isset($result['storeMinimumOrder']))
			$form['storeMinimumOrder']['#default_value'] = $result['storeMinimumOrder'];
			
		$form['textureConfig'] = [
			'#type' => 'fieldset',
			'#title' => 'Конфигурация текстуры',
			'#prefix' => '<div id="textureConfig-fieldset-wrapper">',
			'#suffix' => '</div>',
			'#collapsible' => true,
			//'#collapsed'   => true,
		];

		$form['textureConfig']['glossiness'] = array(
			'#type' => 'textfield',
			'#title' => 'Глянец',
			'#attributes' => array(
				' type' => 'number',
			),
		);
		if (isset($result['textureConfig']['glossiness']))
			$form['textureConfig']['glossiness']['#default_value'] = $result['textureConfig']['glossiness'];
			
		$form['textureConfig']['size'] = [
			'#type' => 'fieldset',
			'#title' => 'Конфигурация текстуры',
			'#prefix' => '<div id="textureConfigsize-fieldset-wrapper">',
			'#suffix' => '</div>',
			'#collapsible' => true,
			//'#collapsed'   => true,
		];

		$form['textureConfig']['size']['x'] = array(
			'#type' => 'textfield',
			'#title' => 'X',
			'#attributes' => array(
				' type' => 'number',
			),
		);
		if (isset($result['textureConfig']['size']['x']))
			$form['textureConfig']['size']['x']['#default_value'] = $result['textureConfig']['size']['x'];

		$form['textureConfig']['size']['y'] = array(
			'#type' => 'textfield',
			'#title' => 'Y',
			'#attributes' => array(
				' type' => 'number',
			),
		);
		if (isset($result['textureConfig']['size']['y']))
			$form['textureConfig']['size']['y']['#default_value'] = $result['textureConfig']['size']['y'];

		$form['textureConfig']['size']['z'] = array(
			'#type' => 'textfield',
			'#title' => 'Z',
			'#attributes' => array(
				' type' => 'number',
			),
		);
		if (isset($result['textureConfig']['size']['z']))
			$form['textureConfig']['size']['z']['#default_value'] = $result['textureConfig']['size']['z'];


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
        if($trigger['#parents'][0] === 'manufacturerIds')
            return $form['manufacturerIds'];
        elseif($trigger['#parents'][0] === 'similarFabricIds')
            return $form['similarFabricIds'];
        elseif($trigger['#parents'][0] === 'companionFabricIds')
            return $form['companionFabricIds'];
        elseif($trigger['#parents'][0] === 'tags')
            return $form['tags'];
        elseif($trigger['#parents'][0] === 'colors')
            return $form['colors'];
        elseif($trigger['#parents'][0] === 'materials')
            return $form['materials'];
        elseif($trigger['#parents'][0] === 'careList')
            return $form['careList'];
        elseif($trigger['#parents'][0] === 'detailsList')
            return $form['detailsList'];
        else
            return $form;
    }

    public function typeAjax(FormStateInterface $form_state){
        $trigger = $form_state->getTriggeringElement();
        drupal_set_message(['#type' => 'item', '#markup' => print_r($trigger, true),]);
        if($trigger['#ajax']['wrapper'] === 'manufacturerIds-fieldset-wrapper'){
            $num_res = $form_state->get('manuf');
            $num_txt = 'manuf';
        }
        elseif($trigger['#ajax']['wrapper'] === 'similarFabricIds-fieldset-wrapper'){
            $num_res = $form_state->get('similar');
            $num_txt = 'similar';
        }
        elseif($trigger['#ajax']['wrapper'] === 'companionFabricIds-fieldset-wrapper'){
            $num_res = $form_state->get('comp');
            $num_txt = 'comp';
        }
        elseif($trigger['#ajax']['wrapper'] === 'tags-fieldset-wrapper'){
            $num_res = $form_state->get('tags');
            $num_txt = 'tags';
        }
        elseif($trigger['#ajax']['wrapper'] === 'colors-fieldset-wrapper'){
            $num_res = $form_state->get('colors');
            $num_txt = 'colors';
        }
        elseif($trigger['#ajax']['wrapper'] === 'materials-fieldset-wrapper'){
            $num_res = $form_state->get('material');
            $num_txt = 'material';
        }
        elseif($trigger['#ajax']['wrapper'] === 'careList-fieldset-wrapper'){
            $num_res = $form_state->get('care');
            $num_txt = 'care';
        }
        elseif($trigger['#ajax']['wrapper'] === 'detailsList-fieldset-wrapper'){
            $num_res = $form_state->get('details');
            $num_txt = 'details';
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
		if($trigger['#name'] == 'main_texture_delete') {
			$d = [];
			$r = SofaApiLogic::send('Fabrics', $d, 'DELETE', $_POST['id'].'/textures/main_texture');
			if($r === []) {
				SofaApiLogic::my_goto("Удалена основная текстура");
			} else {
				SofaApiLogic::my_goto(['#type' => 'item', '#markup' => print_r($r, true) ], $_GET);
			}
        }
        elseif($trigger['#name'] == 'normal_map_delete') {
			$d = [];
			$r = SofaApiLogic::send('Fabrics', $d, 'DELETE', $_POST['id'].'/textures/normal_map');
			if($r === []) {
				SofaApiLogic::my_goto("Удалена карта нормалей");
			} else {
				SofaApiLogic::my_goto(['#type' => 'item', '#markup' => print_r($r, true) ], $_GET);
			}
        }
        elseif($trigger['#name'] == 'metallic_gloss_map_delete') {
			$d = [];
			$r = SofaApiLogic::send('Fabrics', $d, 'DELETE', $_POST['id'].'/textures/metallic_gloss_map');
			if($r === []) {
				SofaApiLogic::my_goto("Удалена карта металлических отражений");
			} else {
				SofaApiLogic::my_goto(['#type' => 'item', '#markup' => print_r($r, true) ], $_GET);
			}
        }
        elseif($trigger['#attributes']['button_name'] == 'submit') {
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

}