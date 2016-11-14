<?php

namespace Drupal\honorary_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Source plugin for ceremony content.
 *
 * @MigrateSource(
 *   id = "ceremony"
 * )
 */
class Ceremony extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    /**
     * An important point to note is that your query *must* return a single row
     * for each item to be imported. Here we might be tempted to add a join to
     * pull in relationships in our tables. Doing this would cause the query to
     * return multiple rows for a given node, once per related value, thus
     * processing the same node multiple times, each time with only one of the
     * multiple values that should be imported. To avoid that, we simply query
     * the base node data here, and pull in the relationships in prepareRow()
     * below.
     */
    $query = $this->select('grad_ceremonies', 'c')
                 ->fields('c', ['ceremony_id', 'year', 'campus', 'type']);
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'bid' => $this->t('Ceremony ID'),
      'year' => $this->t('Year of ceremony'),
      'campus' => $this->t('Campus location of ceremony'),
      'type' => $this->t('Type of ceremony')
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'ceremony_id' => [
        'type' => 'integer',
        'alias' => 'c',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    /**
     * Return row as is.
     */
    return parent::prepareRow($row);
  }

}
