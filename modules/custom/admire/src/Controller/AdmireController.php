<?php

namespace Drupal\admire\Controller;
use Drupal\Core\Controller\ControllerBase;

class AdmireController extends ControllerBase {

    public function content() {
        return [
            'title' => 'Список мероприятий',
            'raw_markup' => [
                '#type' => 'markup',
                '#markup' =>
`
<div id="App">
    <div class="container-fluid">
        <div class="row">
            <div class="col-4">
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                  <label class="btn btn-secondary active">
                    <input type="radio" name="options" value="id" id="option1" v-model="sorting"> Id
                  </label>
                  <label class="btn btn-secondary">
                    <input type="radio" name="options" value="name" id="option2" v-model="sorting"> Name
                  </label>
                  <label class="btn btn-secondary">
                    <input type="radio" name="options" value="description" id="option3" v-model="sorting"> Description
                  </label>
                </div>
            </div>
            <div class="col-8">
                <div class="form-group row">
                    <p :is="'label'" for="inputEmail3" class="col-sm-2 col-form-label" v-model="filterName">Name</p>
                    <div class="col-sm-10">
                      <input type="email" class="form-control" id="inputEmail3">
                    </div>
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
</div>
`,
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