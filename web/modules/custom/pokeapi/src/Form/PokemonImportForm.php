<?php

namespace Drupal\pokeapi\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\pokeapi\Service\PokemonService;
use Drupal\taxonomy\Entity\Term;
use Drupal\taxonomy\Entity\Vocabulary;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form to trigger Pokémon import.
 */
class PokemonImportForm extends FormBase {

  /**
   * The Pokémon service.
   *
   * @var \Drupal\pokeapi\Service\PokemonService
   */
  protected $pokemonService;

  /**
   * Constructs a new PokemonImportForm object.
   *
   * @param \Drupal\pokeapi\Service\PokemonService $pokemon_service
   *   The Pokémon service.
   */
  public function __construct(PokemonService $pokemon_service) {
    $this->pokemonService = $pokemon_service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
          $container->get('pokeapi.pokemon_service')
      );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'pokemon_import_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Import Pokémon'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Start batch process.
    $batch = [
      'title' => $this->t('Importing Pokémon names...'),
      'operations' => [
              ['\Drupal\pokeapi\Form\PokemonImportForm::batchProcess', []],
      ],
      'finished' => '\Drupal\pokeapi\Form\PokemonImportForm::batchFinished',
      'init_message' => $this->t('Starting Pokémon import...'),
      'progress_message' => $this->t('Processed @current out of @total.'),
      'error_message' => $this->t('An error occurred during Pokémon import.'),
    ];

    batch_set($batch);
  }

  /**
   * Batch process callback.
   */
  public static function batchProcess(array &$context) {
    // Fetch Pokémon names using the service.
    $pokemon_service = \Drupal::service('pokeapi.pokemon_service');
    if (!isset($context['sandbox']['pokemon_names'])) {
      $context['sandbox']['pokemon_names'] = $pokemon_service->getPokemonLocation();
      $context['sandbox']['total'] = count($context['sandbox']['pokemon_names']);
      $context['sandbox']['current'] = 0;
      $context['sandbox']['errors'] = [];
    }

    // Import Pokémon names into taxonomy terms.
    // Replace with your actual vocabulary machine name.
    $vocabulary_name = 'pokemon';
    $vocabulary = Vocabulary::load($vocabulary_name);

    for ($i = 0; $i < 10 && $context['sandbox']['current'] < $context['sandbox']['total']; $i++) {
      $pokemon = $context['sandbox']['pokemon_names'][$context['sandbox']['current']];
      $term = Term::create([
        'vid' => $vocabulary->id(),
        'name' => self::cleanName($pokemon['name']),
      ]);
      $term->set('field_id', self::getIdFromUrl($pokemon['url']));
      try {
        $term->save();
        $context['sandbox']['current']++;
      }
      catch (\Exception $e) {
        $context['sandbox']['errors'][] = $e->getMessage();
      }

      // Update progress information.
      $context['message'] = t('Importing Pokémon: @current out of @total', [
        '@current' => $context['sandbox']['current'],
        '@total' => $context['sandbox']['total'],
      ]);
      $context['finished'] = $context['sandbox']['current'] / $context['sandbox']['total'];
    }
  }

  /**
   * Batch finished callback.
   */
  public static function batchFinished($success, $results, $operations) {
    if ($success) {
      $message = t('Pokémon import completed successfully.');
    }
    else {
      $message = t('An error occurred during Pokémon import. Please check logs for details.');
      \Drupal::logger('pokeapi')->error('Pokémon import failed: @errors', ['@errors' => implode(', ', $results['errors'])]);
    }

    \Drupal::messenger()->addMessage($message);
  }

  /**
   * Sanitize text.
   */
  private static function cleanName($name) {
    $pokemon_name = str_replace('-', ' ', $name);
    return ucwords($pokemon_name);
  }

  /**
   * Extract id from url.
   */
  private static function getIdFromUrl($url) {
    $url_e = explode('/', $url);
    return $url_e[count($url_e) - 2];
  }

}
