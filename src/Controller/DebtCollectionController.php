<?php

namespace Drupal\debt_collection\Controller;

use Drupal\Core\Controller\ControllerBase;
class DebtCollectionController extends ControllerBase {

  /**
   * Returns a renderable array for a page.
   *
   * return []
   */
  public function content(): array
  {
    $build = [];
    $build['set_owed_amount_form'] = $this->formBuilder()->getForm('\Drupal\debt_collection\Form\SetOwedAmountForm');

    return $build;
  }
}
