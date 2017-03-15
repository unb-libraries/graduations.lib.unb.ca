<?php

namespace Drupal\pomp_migration\Plugin\migrate\source;

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
    $query = $this->select('pomp_degree', 'd')
                  ->fields('d', ['id', 'deg_parent', 'year', 'campus',
                  'cerem_type', 'recipient', 'degree', 'valedictorian',
                  'gender', 'image', 'caption', 'caption2', 'orator',
                  'citation', 'notes']);
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
      'deg_parent' => $this->t('Degree parent'),
      'year' => $this->t('Year'),
      'campus' => $this->t('Campus'),
      'cerem_type' => $this->t('Ceremony type'),
      'recipient' => $this->t('Recipient name'),
      'degree' => $this->t('Degree'),
      'valedictorian' => $this->t('Valedictorian'),
      'gender' => $this->t('Recipient gender'),
      'image' => $this->t('Image'),
      'caption' => $this->t('Image caption'),
      'caption2' => $this->t('Second image caption'),
      'orator' => $this->t('Orator'),
      'citation' => $this->t('Citation'),
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
        'alias' => 'd',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    /**
     * prepareRow runs after a row is fetched. It will be used to create a temp
     * file from the original blob, copy that file to Drupal, and return the
     * reference for our target field to use.
     */

    /**
     * Process fields that will be translated into taxonomy term indexes
     */

    $deg = $row->getSourceProperty('degree');
    switch ($deg) {
      case "D.C.L.":
        $row->setSourceProperty('deg_id', 6);
        break;
      case "D.Litt.":
        $row->setSourceProperty('deg_id', 10);
        break;
      case "D.Sc.":
        $row->setSourceProperty('deg_id', 8);
        break;
      case "LL.D.":
        $row->setSourceProperty('deg_id', 7);
        break;
      case "M.A.":
        $row->setSourceProperty('deg_id', 9);
        break;
      case "M.Sc.":
        $row->setSourceProperty('deg_id', 12);
        break;
      case "Ph.D.":
        $row->setSourceProperty('deg_id', 11);
        break;
      default:
        $row->setSourceProperty('deg_id', 13);
        break;
    }

    $val = $row->getSourceProperty('valedictory');
    switch ($val) {
      case "Yes":
        $row->setSourceProperty('val_id', 72);
        break;
      case "No":
        $row->setSourceProperty('val_id', 73);
        break;
      default:
        $row->setSourceProperty('val_id', 73);
        break;
    }

    $gen = $row->getSourceProperty('gender');
    switch ($gen) {
      case "Female":
        $row->setSourceProperty('gen_id', 1);
        break;
      case "Male":
        $row->setSourceProperty('gen_id', 2);
        break;
      case "Unspecified":
        $row->setSourceProperty('gen_id', 3);
        break;
    }

    /**
     * Create temp file and write blob's contents to it (if there is one).
     */

    $blob = $row->getSourceProperty('image');

    if (empty($blob)) {
      $row->setSourceProperty('image', null);
    }
    else {

      /**
       * Create temp file.
       */

      $temp = tmpfile();
      fwrite($temp, $blob);

      /**
       * Recover temp file uri.
       */

      $temp_uri = stream_get_meta_data($temp)['uri'];

      /**
       * Build unique, somewhat descriptive file name.
       */

      $name = $row->getSourceProperty('recipient');
      $degree = $row->getSourceProperty('degree');
      $id = (string)$row->getSourceProperty('id');

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
      $file->setPermanent();
      $file->save();

      $row->setSourceProperty('image', $file);
    }

    return parent::prepareRow($row);
  }
}
