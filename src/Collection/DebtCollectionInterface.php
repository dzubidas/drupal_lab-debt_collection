<?php

namespace Drupal\debt_collection\Collection;

interface DebtCollectionInterface
{
  /**
   * @param float $owed_amount
   * @return float.
   */
  public function collectDebt(float $owed_amount):float;
}
