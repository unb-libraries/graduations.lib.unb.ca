<?php

namespace Drupal\honorary_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;
use Drupal\file\Entity\File;

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
      'degree' => $this->t('Degree'),
      'name' => $this->t('Recipient name'),
      'gender' => $this->t('Recipient gender'),
      'valedictorian' => $this->t('Valedictorian'),
      'orator' => $this->t('Orator'),
      'citation' => $this->t('Citation'),
      'image' => $this->t('Image'),
      'img_caption' => $this->t('Image caption'),
      'img_caption_2' => $this->t('Second image caption')
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
     * prepareRow runs after a row is fetched. It will be use to create a temp
     * file from the original blob, copy that file to Drupal, and return the
     * reference for our target field to use.
     */

    /**
     * Create temp file and write blob's contents to it.
     */

    $temp = tmpfile();
    fwrite($temp, $row->getSourceProperty('image'));

    /**
     * Recover temp file uri.
     */

    $temp_uri = stream_get_meta_data($temp)['uri'];

    /**
     * Build unique, somewhat descriptive file name.
     */

    $name = $row->getSourceProperty('name');
    $degree = $row->getSourceProperty('degree');
    $id = (string)$row->getSourceProperty('degree_id');

    /**
     * Restrict filename to alphanumeric (and '_') and assign jpg extension.
     */

    $filename_string = $name . '_' . $degree . '_' . $id;
    $filename = preg_replace("/[^a-zA-Z0-9_]+/", "", $filename_string) . '.jpg';

    /**
     * Copy file, create file reference, pass it to source image field.
     */

    $file_destination = "public://$filename";
    $uri = file_unmanaged_copy($temp_uri, $file_destination,
      FILE_EXISTS_REPLACE);
    $file = File::Create(['uri' => $uri]);
    $row->setSourceProperty('image', $file);

    return parent::prepareRow($row);
  }
}
