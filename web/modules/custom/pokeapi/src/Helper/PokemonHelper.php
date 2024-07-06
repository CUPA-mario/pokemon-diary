<?php

namespace Drupal\pokeapi\Helper;

use Drupal\file\Entity\File;

class PokemonHelper {
  public static function getDefaultSprite($args) {
    $results = \Drupal::service('pokeapi.pokemon_service')->getPokemonByName($args['pokemon_name']) ?? [];
    $sprite = $results['sprites']['other']['official-artwork']['front_default'];
    $file = File::load($args['pokemon_image'] ?? '');
    if (empty($file) && !empty($sprite)) {
      if ($sprite) {
        $args['pokemon_image'] = $sprite;
        $file = File::load($args['pokemon_image']);
        if (!$file) {
          $file = File::create([
            'uri' => $args['pokemon_image'],
            'status' => 1,
          ]);
          $file->save();
        }
      }
    }
    return $file;
  }

  public static function getType($pokemon_name) {
    $results = \Drupal::service('pokeapi.pokemon_service')->getPokemonByName($pokemon_name) ?? [];
    if ($results && $results['types']) {
      foreach ($results['types'] as $type) {
        $types[] = ucwords($type['type']['name']);
      }
    }
    return $types;
  }
}
