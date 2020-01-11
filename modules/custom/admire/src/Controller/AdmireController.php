<?php

namespace Drupal\admire\Controller;

use Drupal\Core\Controller\ControllerBase;

class AdmireController extends ControllerBase {

    public function content() {
        $form['search'] = array(
            '#type' => 'textfield',
            '#title' => 'Название',
            '#name' => 'filterTitle',
            '#attributes' => array('v-model' => 'filterTitle')
        );
        return [
            'title' => 'Список видео',
            'body' => [
                '#prefix' => '<div id="AppList" class="d-none">',
                '#sufix' => '</div>',
                'search' => $form,
            ],
            '#attached' => [
                'library' => [
                    'admire/vue',
                    'admire/jquery',
                    'admire/popper',
                    'admire/bootstrap',
                    'admire/appList',
                ],
            ]
        ];
    }
}