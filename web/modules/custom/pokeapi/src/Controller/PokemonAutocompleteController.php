<?php

namespace Drupal\pokeapi\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\taxonomy\Entity\Term;

/**
 * Controller for handling taxonomy autocomplete.
 */
class PokemonAutocompleteController extends ControllerBase {

  /**
   * Returns autocomplete suggestions for taxonomy terms.
   *
   * @param string $vocabulary
   *   The machine name of the taxonomy vocabulary.
   *
   * @return JsonResponse
   *   JSON response with matching taxonomy term labels.
   */
  public function taxonomyAutocomplete($vocabulary) {
    $terms = \Drupal::entityTypeManager()
      ->getStorage('taxonomy_term')
      ->loadByProperties(['vid' => $vocabulary]);

    $matches = [];
    /** @var Term $term */
    foreach ($terms as $term) {
      $matches[] = [
        'value' => $term->getName(),
        'label' => $term->getName(),
      ];
    }

    return new JsonResponse($matches);
  }
}
