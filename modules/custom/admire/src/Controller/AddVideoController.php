<?php

namespace Drupal\admire\Controller;

use Drupal\Core\Controller\ControllerBase;

class AddVideoController extends ControllerBase {

    public function content() {
        $form['title'] = array(
            '#type' => 'textfield',
            '#title' => 'Название',
            '#name' => 'title',
            '#attributes' => array('v-model' => 'title')
        );
        $form['description'] = array(
            '#type' => 'textarea',
            '#title' => 'Описание',
            '#name' => 'description',
            '#attributes' => array('v-model' => 'description')
        );
        $form['isPaid'] = array(
            '#type' => 'checkbox',
            '#title' => 'Является ли платным',
            '#name' => 'isPaid',
            '#attributes' => array('v-model' => 'isPaid')
        );
        $form['price'] = array(
            '#type' => 'number',
            '#title' => 'Цена',
            '#name' => 'price',
            '#attributes' => array(
                'v-model' => 'price',
                ':disabled' => '!isPaid'
            )
        );
        $form['video'] = array(
            '#type' => 'file',
            '#title' => 'Загрузите видео',
            '#name' => 'video',
            '#attributes' => array(
                '@change' => 'addVideo',
                'id' => 'inputVideo'
            )
        );
        $form['preview'] = array(
            '#type' => 'file',
            '#title' => 'Загрузите превью',
            '#name' => 'preview',
            '#attributes' => array(
                '@change' => 'addPreview',
                'id' => 'inputPreview'
            )
        );
        $form['submit'] = array(
            '#type' => 'button',
            '#title' => 'Сохранить',
            '#attributes' => array(
                '@click' => 'submit',
                ':disabled' => 'isDisabled'
            )
        );
        return [
            'title' => 'Добавить видео',
            'body' => [
                '#prefix' => '<div id="AppAdd d-none" class="pb-5">',
                '#sufix' => '</div>',
                'addForm' => $form,
                'preview' => [
                    '#type' => 'markup',
                    '#markup' =>
                        '
<div v-show="previewImage != null">
    <div v-html="previewImage" class="w-100"></div>
</div>
'
                ]
            ],
            '#attached' => [
                'library' => [
                    'admire/vue',
                    'admire/jquery',
                    'admire/popper',
                    'admire/bootstrap',
                    'admire/appAdd',
                ],
            ]
        ];
    }
}