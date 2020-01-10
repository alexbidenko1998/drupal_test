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

class LegendaryStatusBlockForm extends FormBase {
    /**
     * {@inheritdoc}.
     */
    // Метод для котороый возвращает ид формы.
    public function getFormId() {
        return 'legendary_status_block_form';
    }

    /**
     * {@inheritdoc}.
     */
    // Вместо hook_form.
    public function buildForm(array $form, FormStateInterface $form_state) {

        $error = [ '#type' => 'item', '#markup' => 'Error',];

        $method = 'getLegendaryInfo';
        $data = [];
        $result = HockeyApiLogic::send($method, $data);

        if ($result == NULL) {
            return [
                '#type' => 'item',
                '#markup' => $error,
            ];
        }
		

        $form['table'] = array(
            '#type' => 'table',
            '#header' => array('Категория','Уровень +','Цена'),
        );

        $form['table'][] = [
			'name' => [
				'#type' => 'item',
				'#markup' => 'R1',
            ],
			'level' => [
				'#type' => 'item',
				'#markup' => $result['info']['R1']['lvl'],
            ],
			'cost' => [
				'#type' => 'item',
				'#markup' => $result['info']['R1']['cost'],
            ],
        ];

        $form['table'][] = [
			'name' => [
				'#type' => 'item',
				'#markup' => 'R2',
            ],
			'level' => [
				'#type' => 'item',
				'#markup' => $result['info']['R2']['lvl'],
            ],
			'cost' => [
				'#type' => 'item',
				'#markup' => $result['info']['R2']['cost'],
            ],
        ];

        $form['table'][] = [
			'name' => [
				'#type' => 'item',
				'#markup' => 'R3',
            ],
			'level' => [
				'#type' => 'item',
				'#markup' => $result['info']['R3']['lvl'],
            ],
			'cost' => [
				'#type' => 'item',
				'#markup' => $result['info']['R3']['cost'],
            ],
        ];

        $form['table'][] = [
			'name' => [
				'#type' => 'item',
				'#markup' => 'R4',
            ],
			'level' => [
				'#type' => 'item',
				'#markup' => $result['info']['R4']['lvl'],
            ],
			'cost' => [
				'#type' => 'item',
				'#markup' => $result['info']['R4']['cost'],
            ],
        ];

        $form['table'][] = [
			'name' => [
				'#type' => 'item',
				'#markup' => 'R5',
            ],
			'level' => [
				'#type' => 'item',
				'#markup' => $result['info']['R5']['lvl'],
            ],
			'cost' => [
				'#type' => 'item',
				'#markup' => $result['info']['R5']['cost'],
            ],
        ];

        $form['table'][] = [
			'name' => [
				'#type' => 'item',
				'#markup' => 'R6',
            ],
			'level' => [
				'#type' => 'item',
				'#markup' => $result['info']['R6']['lvl'],
            ],
			'cost' => [
				'#type' => 'item',
				'#markup' => $result['info']['R6']['cost'],
            ],
        ];

        $form['table'][] = [
			'name' => [
				'#type' => 'item',
				'#markup' => 'R0',
            ],
			'level' => [
				'#type' => 'item',
				'#markup' => $result['info']['R0']['lvl'],
            ],
			'cost' => [
				'#type' => 'item',
				'#markup' => $result['info']['R0']['cost'],
            ],
        ];

        $form['table'][] = [
			'name' => [
				'#type' => 'item',
				'#markup' => 'T1',
            ],
			'level' => [
				'#type' => 'item',
				'#markup' => $result['info']['T1']['lvl'],
            ],
			'cost' => [
				'#type' => 'item',
				'#markup' => $result['info']['T1']['cost'],
            ],
        ];

        $form['table'][] = [
			'name' => [
				'#type' => 'item',
				'#markup' => 'T2',
            ],
			'level' => [
				'#type' => 'item',
				'#markup' => $result['info']['T2']['lvl'],
            ],
			'cost' => [
				'#type' => 'item',
				'#markup' => $result['info']['T2']['cost'],
            ],
        ];

        $form['table'][] = [
			'name' => [
				'#type' => 'item',
				'#markup' => 'T3',
            ],
			'level' => [
				'#type' => 'item',
				'#markup' => $result['info']['T3']['lvl'],
            ],
			'cost' => [
				'#type' => 'item',
				'#markup' => $result['info']['T3']['cost'],
            ],
        ];

        $form['table'][] = [
			'name' => [
				'#type' => 'item',
				'#markup' => 'T4',
            ],
			'level' => [
				'#type' => 'item',
				'#markup' => $result['info']['T4']['lvl'],
            ],
			'cost' => [
				'#type' => 'item',
				'#markup' => $result['info']['T4']['cost'],
            ],
        ];

        $form['table'][] = [
			'name' => [
				'#type' => 'item',
				'#markup' => 'T5',
            ],
			'level' => [
				'#type' => 'item',
				'#markup' => $result['info']['T5']['lvl'],
            ],
			'cost' => [
				'#type' => 'item',
				'#markup' => $result['info']['T5']['cost'],
            ],
        ];

       

        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {

    }

}