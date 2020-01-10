<?php

namespace Drupal\admire\Controller;
use Drupal\Core\Controller\ControllerBase;

class AdmireController extends ControllerBase {

    public function content() {
        drupal_add_js('https://cdn.jsdelivr.net/npm/vue@2.6.11', 'external');

        return [
            'title' => 'Список мероприятий',
            'raw_markup' => [
                '#type' => 'markup',
                '#markup' =>
'
<div id="App">
    <table class="table">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Название</th>
          <th scope="col">Описание</th>
          <th scope="col">Ссылка</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="event in events">
          <th scope="row">{{event}}</th>
          <td>Mark</td>
          <td>Otto</td>
          <td>@mdo</td>
        </tr>
      </tbody>
    </table>
</div>
'
            ]
        ];
    }
}