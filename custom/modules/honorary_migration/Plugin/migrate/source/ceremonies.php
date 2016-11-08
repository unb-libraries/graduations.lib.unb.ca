<?php

namespace Drupal\honorary_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\souce\SqlBase;

 /**
  * Source plugin for ceremonies.
  *
  * @MigrateSource(
  *  id = "ceremonies"
  * )
  */
 class Ceremonies extends SqlBase {

   /**
    * {@inheritdoc}
    */
   public function query() {
     $query = $this->select('ceremonies', 'c')
       ->fields('c', ['ceremony_id', 'year', 'campus', 'type']);
     return $query;
   }

   /**
    * {@inheritdoc}
    */
   public function fields() {
     $fields = [
       'ceremony_id'  => $this->t('Ceremony ID'),
       'year'         => $this->t('Year'),
       'campus'       => $this->t('Campus'),
       'type'         => $this->t('Type')
     ];

     return $fields;
   }

   /**
    * {@inheritdoc}
    */
   public function getIds() {
     return [
       'ceremony_id' => [
         'type'   => 'integer',
         'alias'  => 'c'
       ]
     ];
   }
 }
