<?php

namespace Drupal\admire\Controller;
use Drupal\Core\Controller\ControllerBase;

class AdmireController extends ControllerBase {

    public function content() {
        $form['search'] = array(
            '#type' => 'text',
            '#title' => 'Имя',
            '#v-model' => 'filterName'
        );
        return [
            'title' => 'Список мероприятий',
            'raw_markup' => [
                '#prefix' => '<div id="App">',
                '#sufix' => '</div>',
                'search' => $form,
                'table' => [
                    '#type' => 'markup',
                    '#markup' =>
'
<div class="container-fluid">
    <div class="row">
        <div class="col-4">
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
              <p :is="label" class="btn btn-secondary active">
                <img :is="input" type="radio" name="options" value="id" id="option1" v-model="sorting"> Id
              </p>
              <p :is="label" class="btn btn-secondary">
                <img :is="input" type="radio" name="options" value="name" id="option2" v-model="sorting"> Name
              </p>
              <p :is="label" class="btn btn-secondary">
                <img :is="input" type="radio" name="options" value="description" id="option3" v-model="sorting"> Description
              </p>
            </div>
        </div>
    </div>
</div>

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