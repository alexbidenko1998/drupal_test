<?php

namespace Drupal\admire\Controller;

use Drupal\Core\Controller\ControllerBase;

class RedactVideoController extends ControllerBase {

    public function content($videoId) {
        return [
            'title' => 'Редактировать видео',
            'body' => [
                '#prefix' => '<div id="AppAdd" v-show="videoData" video-id="' . $videoId . '">',
                '#sufix' => '</div>',
                //'addForm' => $form,
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