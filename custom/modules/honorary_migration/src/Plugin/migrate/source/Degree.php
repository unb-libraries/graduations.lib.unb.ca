<?php

namespace Drupal\honorary_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Source plugin for ceremony content.
 *
 * @MigrateSource(
 *   id = "degree"
 * )
 */
class Degree extends SqlBase {

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
    $query = $this->select('degrees', 'd')
                  ->fields('d', ['degree_id', 'ceremony_id', 'degree',
                  'name', 'gender', 'valedictorian', 'orator', 'citation',
                  'image', 'img_caption', 'img_caption_2']);
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
      'degree_id' => $this->t('Degree ID'),
      'ceremony_id' => $this->t('Ceremony ID'),
      'degree' => $this->t('Degree')
      'name' => $this->t('Recipient name'),
      'gender' => $this->t('Recipient gender'),
      'valedictorian' => $this->t('Valedictorian'),
      'orator' => $this->t('Orator'),
      'citation' => $this->t('Citation'),
      'image' => $this->t('Image'),
      'img_caption' => $this->t('Image caption'),
      'img_caption_2' => $this->t('Second image caption'),
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'degree_id' => [
        'type' => 'integer',
        'alias' => 'd',
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
