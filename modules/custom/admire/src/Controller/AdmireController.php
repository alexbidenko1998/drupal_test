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
                '#prefix' => '<div id="AppList">',
                '#sufix' => '</div>',
                'search' => $form,
                'table' => [
                    '#type' => 'markup',
                    '#markup' =>
'
<table class="table">
  <thead>
    <tr>
      <th scope="col">Id</th>
      <th scope="col">Название</th>
      <th scope="col">Описание</th>
      <th scope="col">Превью</th>
    </tr>
  </thead>
  <tbody>
    <tr v-for="video in filteredVideos">
      <th scope="row">{{video.id}}</th>
      <td>{{video.title}}</td>
      <td>{{video.description}}</td>
      <td>
        <img class="w-100" :id="video.preview" v-bind:src="video.preview">
       </td>
    </tr>
  </tbody>
</table>
'
                ]
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