<?php

namespace Drupal\admire\Controller;
use Drupal\Core\Controller\ControllerBase;

class AdmireController extends ControllerBase {

    public function content() {
        $form['search'] = array(
            '#type' => 'textfield',
            '#title' => 'Имя',
            '#name' => 'filterName',
            '#attributes' => array('v-model' => 'filterName')
        );
        $form['submit'] = array(
            '#type' => 'button',
            '#name' => 'Обновить',
            '#attributes' => array('@click' => 'update')
        );
        return [
            'title' => 'Список мероприятий',
            'body' => [
                '#prefix' => '<div id="App">',
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
      <th scope="col">Ссылка</th>
    </tr>
  </thead>
  <tbody>
    <tr v-for="event in filteredEvents">
      <th scope="row">{{event.id}}</th>
      <td>{{event.name}}</td>
      <td>{{event.description}}</td>
      <td><a :href="event.site">{{event.site}}</td>
    </tr>
  </tbody>
</table>

<div class="modal fade bd-example-modal-sm" id="success" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      Обновление успешно завершено
    </div>
  </div>
</div>
'
                ]
            ],
            '#attached' => [
                'library' => [
                    'admire/vue',
                    'admire/app',
                ],
            ]
        ];
    }
}