<?php

namespace Drupal\pokeapi\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller for handling taxonomy autocomplete.
 */
class PokemonAutocompleteController extends ControllerBase {

  /**
   * Retrieves the entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new PokemonAutocompleteController object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   Provides an interface for entity type managers.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
          $container->get('entity_type.manager')
      );
  }

  /**
   * Returns autocomplete suggestions for taxonomy terms.
   *
   * @param string $vocabulary
   *   The machine name of the taxonomy vocabulary.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response with matching taxonomy term labels.
   */
  public function taxonomyAutocomplete($vocabulary) {
    $terms = $this->entityTypeManager()
      ->getStorage('taxonomy_term')
      ->loadByProperties(['vid' => $vocabulary]);

    $matches = [];
    /** @var \Drupal\taxonomy\Entity\Term $term */
    foreach ($terms as $term) {
      $matches[] = [
        'value' => $term->getName(),
        'label' => $term->getName(),
      ];
    }

    return new JsonResponse($matches);
  }

}
