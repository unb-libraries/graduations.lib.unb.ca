<?php

namespace Drupal\honorary_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Source plugin for ceremony content.
 *
 * @MigrateSource(
 *   id = "address"
 * )
 */
class Address extends SqlBase {

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
    $query = $this->select('addresses', 'a')
                  ->fields('a', ['address_id', 'ceremony_id', 'type',
                  'delivered_by', 'content']);
    return $query;
  }

  /**
   * The names on the left are internal to the migration, the ones on the right
   * are code-meaningless descriptions. Mapping is done in migration YAML file.
   *
   * {@inheritdoc}
   *
   */
  public function fields() {
    $fields = [
      'address_id' => $this->t('Address ID'),
      'ceremony_id' => $this->t('Ceremony ID'),
      'type' => $this->t('Type of address')
      'delivered_by' => $this->t('Delivered by'),
      'content' => $this->t('Address content')
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'address_id' => [
        'type' => 'integer',
        'alias' => 'a',
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
