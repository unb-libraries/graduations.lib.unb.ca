<?php

namespace Drupal\pomp_core\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a custom Footer block for the page.
 *
 * @Block(
 *   id = "pomp_footer",
 *   admin_label = @Translation("Honorary Footer"),
 *   category = @Translation("Misc"),
 * )
 */
class PompFooter extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $text = '
      <div id="footer-menu">
        <br>
        All contents © ' . date('Y') . '
          <a href="https://www.lib.unb.ca/archives/">
            Archives &amp; Special Collections</a>
          and
          <a href="https://www.lib.unb.ca/">UNB Libraries</a>
        </div>

      <div id="footer-logo">
        <a href="https://www.lib.unb.ca/">
          <img alt="UNB Libraries Logo" id="unb-lib-logo" src="/themes/custom/pomp_subtheme_bs/images/unb-libraries-red-black.png" />
        </a>
      </div>
    ';

    return [
      '#markup' => $this->t($text),
    ];
  }

}
