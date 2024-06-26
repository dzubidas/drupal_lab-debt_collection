<?php

namespace Drupal\debt_collection\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\debt_collection\Collection\Rocky;

#[Block(
  id: "debt_collection_block",
  admin_label: new TranslatableMarkup('Debt Collelction Block'),
)]

class DebtCollectionBlock extends  BlockBase
{
  public function build()
  {
    $user_service = \Drupal::service('debt_collection.user_load_service');
    $all_users = $user_service->loadAllUsers();
    $user_names = [];

    $debt_service = \Drupal::service('debt_collection.debt_collection_service');
    $user_id = 1;
    $collector = new Rocky();
    $collected_amount = $debt_service->collectDebtForUser($user_id, $collector);


    return \Drupal::formBuilder()->getForm('Drupal\debt_collection\Form\DebtCollectionForm');
  }
}
