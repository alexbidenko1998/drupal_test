<?php
/**
 * Created by PhpStorm.
 * User: saint
 * Date: 26.02.2018
 * Time: 12:38
 */
namespace Drupal\hockey_page\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormInterface;

/**
 * Provides a 'send_push_block' block.
 *
 * @Block(
 *   id = "send_push_block",
 *   admin_label = @Translation("Отправить Push"),
 *   category = @Translation("Hockey pages")
 * )
 */
class SendPushBlock extends BlockBase {
    /**
     * {@inheritdoc}
     */
    public function build() {

        $form = \Drupal::formBuilder()->getForm('Drupal\hockey_page\Form\SendPush');
        return $form;

    }
}