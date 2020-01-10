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

class DistributorEdit extends FormBase {

    /**
     * {@inheritdoc}.
     */
    // Метод для котороый возвращает ид формы.
    public function getFormId() {
        return 'DistributorEdit_form';
    }

    /**
     * {@inheritdoc}.
     */
    // Вместо hook_form.
    public function buildForm(array $form, FormStateInterface $form_state, $distributorId = '')
    {

		$method  = 'Distributors';
		$result = SofaApiLogic::send($method, $result, 'GET', $distributorId);

        $form['#tree'] = TRUE;
        $form['#prefix'] = '<div id="all-fieldset-wrapper">';
        $form['#suffix'] = '</div>';
		
        $form['id'] = array(
            '#type' => 'hidden',
            '#default_value' => $distributorId,
        );
		
        $form['label'] = array(
            '#type' => 'label',
            '#title' => $distributorId,
        );
		

        $form['description'] = array(
            '#type' => 'textfield',
            '#title' => 'Описание',
            '#required' => TRUE,
        );
		
        if (isset($result['description']))
            $form['description']['#default_value'] = $result['description'];

        $form['email'] = array(
            '#type' => 'textfield',
            '#title' => 'email',
        );
        if (isset($result['email']))
            $form['email']['#default_value'] = $result['email'];

        $form['address'] = array(
            '#type' => 'textfield',
            '#title' => 'Адресс',
        );
        if (isset($result['address']))
            $form['address']['#default_value'] = $result['address'];

        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => 'Обновить',
            '#attributes' => [
                'class' => ['col-xs-12', 'btn-info']
            ]
        );
		
		
		
        $itemCount = 10;

        $page = $_GET['page'] ?? 0;
        if($page < 0 ){ $page=0; }

        if(isset($_POST['type']) ) {
            if($_POST['type'] == 'Delete' && isset($_POST['id'])){
                $d = [];
                $r = SofaApiLogic::send('Distributors', $d, 'DELETE', $distributorId.'/projects/'.$_POST['id']);
                if($r === []) {
					SofaApiLogic::my_goto("Удалена проект: {$_POST['id']}");
                } else {
                    SofaApiLogic::my_goto(['#type' => 'item', '#markup' => print_r($r, true) ], $_GET);
                }
            }
        }
        
		$method = 'Distributors';
        $data = [];

        $result = SofaApiLogic::send($method, $data, 'GET', $distributorId.'/projects?page='.$page.'&size='.$itemCount);

        $header = array(
            'id' => array('data' => 'id', 'field' => 'id'),
            'description' => array('data' => 'Описания', 'field' => 'description'),
            'createdAt' => array('data' => 'Создано', 'field' => 'createdAt'),
            'updatedAt' => array('data' => 'Измененно', 'field' => 'updatedAt'),
            '',
            '',
        );

        $form['form'] = [
            '#type' => 'form',
            '#method' => 'get',
        ];


        $form['table'] = array(
            '#type' => 'table',
            // '#caption' => $this->t('Sample Table'),
            '#header' => $header,
            '#empty' => $empty,
            '#prefix' => '<div class="col-xs-12"><div class="row">', '#suffix' => '</div></div>',
            '#weight' => 0,
        );
		if($result != NULL){
			foreach ($result['values'] as $item){
				$id = $item['id'];
				$description =  $item['description'] ?? NULL;
				$createdAt =  $item['createdAt'] ?? NULL;
				$updatedAt =  $item['updatedAt'] ?? NULL;
				$playerUrl = '<a href="/project/'.$item['id'].'/edit" class="btn btn-default col-xs-12 glyphicon glyphicon-list-alt"> Подробнее</a>';

				$deleteAction = [
					'#type' => 'form',
					'#method' => 'post',
					'type'       => [ '#type'=>'hidden', '#name'=> 'type', '#value' => 'Delete', ],
					'id'   => [ '#type'=>'hidden', '#name'=> 'id', '#value' => $id ],
					'action' => ['#type'=>'submit'   , '#value' => 'Удалить', '#attributes' => [
						'onclick' => 'if(!confirm("Удалить проект?")){return false;}',
						'class'=>['col-xs-12', 'btn-danger'], ], ],
				];
				

					
				$form['table'][] = [
					'id' => [
						'#type' => 'item',
						'#markup' => $id,
					],
					'description'  => [
						'#type' => 'item',
						'#markup' => $description,
					],
					'createdAt'  => [
						'#type' => 'item',
						'#markup' => $createdAt,
					],
					'updatedAt'  => [
						'#type' => 'item',
						'#markup' => $updatedAt,
					],
					'links' => [
						'#type' => 'item',
						'#markup' => $playerUrl,
					],
					'deleteAction' => $deleteAction,
				];
			}
			

			$pageCount = $result['page']['totalElements']/$itemCount;
			pager_default_initialize($pageCount, 1);

			$form['pager'] = [
				'#type' => 'pager',
				'#quantity' => 5,
			];
		
		}
		
        return $form;
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
			'description' => $setting['description'],
			'email' => $setting['email'],
			'address' => $setting['address']
		];
		
		
		
        $method  = 'Distributors';
        $result = SofaApiLogic::send($method, $result, 'PUT', $setting['id']);
		
        if($result === []){
            drupal_set_message('Успешно');
            //drupal_set_message(['#type' => 'item', '#markup' => print_r($result, true),]);
        } else {
            drupal_set_message('Не обновлено', 'error');
        }
    }

}