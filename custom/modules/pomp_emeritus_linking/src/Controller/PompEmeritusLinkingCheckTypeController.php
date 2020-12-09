<?php

namespace Drupal\pomp_emeritus_linking\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

/**
 * PompEmeritusLinkingCheckTypeController object.
 */
class PompEmeritusLinkingCheckTypeController extends ControllerBase {

  /**
   * Check to see if a node is a ceremony node.
   *
   * @param int $node
   *   The node ID to check.
   *
   * @return bool
   *   TRUE if the node is type honorary_ceremony. FALSE otherwise.
   */
  public function checkType($node) {
    $actual_node = Node::load($node);
    return AccessResult::allowedIf($actual_node->bundle() === 'honorary_ceremony');
  }

}
