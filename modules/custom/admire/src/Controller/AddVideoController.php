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
                'v-show' => 'isPaid'
            )
        );
        $form['video'] = array(
            '#type' => 'file',
            '#title' => 'Загрузите видео',
            '#name' => 'video',
            '#attributes' => array('v-model' => 'video')
        );
        $form['preview'] = array(
            '#type' => 'file',
            '#title' => 'Загрузите превью',
            '#name' => 'preview',
            '#attributes' => array('@change' => 'addVideo(this)')
        );
        $form['preview'] = array(
            '#type' => 'file',
            '#title' => 'Загрузите превью',
            '#name' => 'preview',
            '#attributes' => array('@change' => 'addPreview(this)')
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
                '#prefix' => '<div id="AppAdd">',
                '#sufix' => '</div>',
                'addForm' => $form,
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