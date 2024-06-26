<?php

namespace Drupal\debt_collection;

use Drupal\debt_collection\Collection\DebtCollectionInterface;
use Drupal\debt_collection\Services\UserLoadService;

/**
 * Service for handling a debt.
*/
  class DebtCollectionService
{
  protected UserLoadService $user_service;

  public function __construct(UserLoadService $user_service)
  {
    $this->user_service = $user_service;
  }

  /**
   * Collect debt for specific user
   *
   * @param int $user_id
   * @param DebtCollectionInterface $collector
   * @return float
   **/
  public function collectDebtForUser(int $user_id, DebtCollectionInterface $collector): float
  {
    $user = $this->user_service->loadUserById($user_id);

    if ($user && !$user->isAnonymous() && $user->hasField('owed_amount') && !$user->get('owed_amount')->isEmpty()) {
      $owed_amount = $user->get('owed_amount')->value;
      return $collector->collectDebt($owed_amount);
    }

    return 0;
  }
}
