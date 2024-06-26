<?php

namespace Drupal\debt_collection\Collection;

class CollectionAgency implements DebtCollectionInterface
{
  public function collectDebt(float $owed_amount): float
  {
    $guaranted = $owed_amount * 0.5;

    return $guaranted;
  }
}
