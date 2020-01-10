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
 * Provides a 'settings_menu_block' block.
 *
 * @Block(
 *   id = "settings_menu_block",
 *   admin_label = @Translation("Настройки"),
 *   category = @Translation("Hockey pages")
 * )
 */
class SettingsEditMenuBlock extends BlockBase {
    /**
     * {@inheritdoc}
     */
    public function build() {

        $form = \Drupal::formBuilder()->getForm('Drupal\hockey_page\Form\SettingsEditMenuBlockForm');
        return $form;

    }
}