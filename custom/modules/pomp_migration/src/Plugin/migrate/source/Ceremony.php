<?php

namespace Drupal\pomp_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;
use Drupal\file\Entity\File;
use Drupal\node\Entity\Node;

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
    $query = $this->select('pomp_cerem', 'c')
                  ->fields('c', ['id', 'year', 'campus', 'type', 'notes']);
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
      'id' => $this->t('Ceremony ID'),
      'year' => $this->t('Year'),
      'campus' => $this->t('Campus'),
      'type' => $this->t('Type'),
      'notes' => $this->t('Notes')
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
        'alias' => 'c',
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
     * Process fields that will be translated into taxonomy term indexes.
     */

    $type = $row->getSourceProperty('type');
    switch ($type) {
      case "1st Academic Awards Ceremony":
        $row->setSourceProperty('type_id', 48);
        $row->setSourceProperty('seas_id', 14);
        break;
      case "2nd Academic Awards Ceremony":
        $row->setSourceProperty('type_id', 49);
        $row->setSourceProperty('seas_id', 14);
        break;
      case "3rd Academic Awards Ceremony":
        $row->setSourceProperty('type_id', 50);
        $row->setSourceProperty('seas_id', 14);
        break;
      case "4th Academic Awards Ceremony":
        $row->setSourceProperty('type_id', 51);
        $row->setSourceProperty('seas_id', 14);
        break;
      case "Academic Awards Ceremony":
        $row->setSourceProperty('type_id', 52);
        $row->setSourceProperty('seas_id', 14);
        break;
      case "Convocation":
        $row->setSourceProperty('type_id', 53);
        $row->setSourceProperty('seas_id', 14);
        break;
      case "Convocation - Ceremony A":
        $row->setSourceProperty('type_id', 54);
        $row->setSourceProperty('seas_id', 14);
        break;
      case "Convocation - Ceremony B":
        $row->setSourceProperty('type_id', 55);
        $row->setSourceProperty('seas_id', 14);
        break;
      case "Encaenia":
        $row->setSourceProperty('type_id', 56);
        $row->setSourceProperty('seas_id', 15);
        break;
      case "Encaenia - Ceremony A":
        $row->setSourceProperty('type_id', 57);
        $row->setSourceProperty('seas_id', 15);
        break;
      case "Encaenia - Ceremony B":
        $row->setSourceProperty('type_id', 58);
        $row->setSourceProperty('seas_id', 15);
        break;
      case "Encaenia - Ceremony C":
        $row->setSourceProperty('type_id', 59);
        $row->setSourceProperty('seas_id', 15);
        break;
      case "Encaenia - Ceremony D":
        $row->setSourceProperty('type_id', 60);
        $row->setSourceProperty('seas_id', 15);
        break;
      case "Special Convocation (April)":
        $row->setSourceProperty('type_id', 62);
        $row->setSourceProperty('seas_id', 42);
        break;
      case "Special Convocation (August)":
        $row->setSourceProperty('type_id', 63);
        $row->setSourceProperty('seas_id', 42);
        break;
      case "Special Convocation (February)":
        $row->setSourceProperty('type_id', 64);
        $row->setSourceProperty('seas_id', 42);
        break;
      case "Special Convocation (July)":
        $row->setSourceProperty('type_id', 65);
        $row->setSourceProperty('seas_id', 42);
        break;
      case "Special Convocation (March)":
        $row->setSourceProperty('type_id', 66);
        $row->setSourceProperty('seas_id', 42);
        break;
      case "Special Convocation (October)":
        $row->setSourceProperty('type_id', 67);
        $row->setSourceProperty('seas_id', 42);
        break;
      case "Special Convocation (September)":
        $row->setSourceProperty('type_id', 68);
        $row->setSourceProperty('seas_id', 42);
        break;
      case "Special Encaenia (March)":
        $row->setSourceProperty('type_id', 69);
        $row->setSourceProperty('seas_id', 42);
        break;
      case "Spring Convocation":
        $row->setSourceProperty('type_id', 70);
        $row->setSourceProperty('seas_id', 42);
        break;
      default:
        $row->setSourceProperty('type_id', 61);
        $row->setSourceProperty('seas_id', 42);
        break;
    }

    $camp = $row->getSourceProperty('campus');
    switch ($camp) {
      case "Saint John":
        $row->setSourceProperty('camp_id', 5);
        break;
      default:
        $row->setSourceProperty('camp_id', 4);
        break;
    }

    $year = $row->getSourceProperty('year');
    $title = (string)$year . " " . $camp . " " . $type;
    $row->setSourceProperty('node_title', $title);

    return parent::prepareRow($row);
  }
}
