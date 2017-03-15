<?php

namespace Drupal\pomp_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;
use Drupal\file\Entity\File;

/**
 * Source plugin for address content.
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
    $query = $this->select('pomp_address', 'a')
                  ->fields('a', ['id', 'add_parent', 'year', 'campus',
                  'cerem_type', 'type', 'delivered_by', 'content']);
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
      'id' => $this->t('Degree ID'),
      'add_parent' => $this->t('Address parent'),
      'year' => $this->t('Year'),
      'campus' => $this->t('Campus'),
      'cerem_type' => $this->t('Ceremony type'),
      'type' => $this->t('Type'),
      'delivered_by' => $this->t('Delivered by'),
      'content' => $this->t('Content')
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'id' => [
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
     * prepareRow runs after a row is fetched.
     */

    /**
     * Process fields that will be translated into taxonomy term indexes
     */

    $type = $row->getSourceProperty('type');
    switch ($type) {
      case "Alumni Oration":
        $row->setSourceProperty('type_id', 44);
        break;
      case "Valedictory Address":
        $row->setSourceProperty('type_id', 46);
        break;
      default:
        $row->setSourceProperty('type_id', 45);
        break;
    }

    return parent::prepareRow($row);
  }
}
