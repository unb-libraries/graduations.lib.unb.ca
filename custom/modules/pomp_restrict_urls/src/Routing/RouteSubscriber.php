<?php
namespace Drupal\pomp_restrict_urls\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    // Deny access to '/user/{uid}/edit}' if not admin.
    if ($route = $collection->get('entity.user.edit_form')) {
      $route->setRequirement('_permission', 'access administration pages');
    }
  }
}
