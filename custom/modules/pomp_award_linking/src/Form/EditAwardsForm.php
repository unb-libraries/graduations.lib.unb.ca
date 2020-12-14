<?php

namespace Drupal\pomp_award_linking\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Views;
use Drupal\Core\Url;

/**
 * EditAwardsForm object.
 */
class EditAwardsForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'pomp_award_linking_edit_awards_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $node = NULL) {
    $form = [];

    // List existing degrees.
    $view = Views::getView('edit_ceremony_awards');
    $view->setDisplay('block_1');
    $view->setArguments([$node]);
    $render = $view->render();
    $form['edit_ceremony_awards_view'] = $render;

    // Add degrees.
    $form['add_award_button'] = [
      '#type' => 'link',
      '#title' => t('Add New Faculty Award'),
      '#url' => Url::fromUri("internal:/node/add/faculty_award"),
      '#attributes' => [
        'class' => ['button'],
      ],
    ];

    $usr = \Drupal::currentUser()->id();
    $ses = \Drupal::service('session_manager')->getId();
    $pomp_award_linking_parent_cer = 'parent_cer' . $usr . $ses;
    \Drupal::state()->set($pomp_award_linking_parent_cer, $node);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

}
