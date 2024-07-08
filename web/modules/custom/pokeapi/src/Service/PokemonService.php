<?php

namespace Drupal\pokeapi\Service;

use Drupal\Component\Utility\Html;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use GuzzleHttp\ClientInterface;

/**
 * Service to fetch Pokémon data.
 */
class PokemonService {

  /**
   * The HTTP client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * The logger factory.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;

  /**
   * Constructs a new PokemonService object.
   *
   * @param \GuzzleHttp\ClientInterface $http_client
   *   The Guzzle HTTP client.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
   *   The logger factory.
   */
  public function __construct(ClientInterface $http_client, LoggerChannelFactoryInterface $logger_factory) {
    $this->httpClient = $http_client;
    $this->loggerFactory = $logger_factory;
  }

  /**
   * Initiates the get request.
   *
   * @param string $path
   *   Resource path.
   * @param mixed $query
   *   Additional options.
   */
  private function execute($path, $query = []) {
    try {
      $response = $this->httpClient->request('GET', 'https://pokeapi.co/api/v2' . $path, [
        'query' => $query,
      ]);
      return json_decode($response->getBody()->getContents(), TRUE);
    }
    catch (\Exception $e) {
      $this->loggerFactory->get('pokeapi')->error('Error fetching Pokémon: @message', ['@message' => $e->getMessage()]);
      return [];
    }
  }

  /**
   * Fetches Pokémon data from PokeAPI.
   *
   * @return array
   *   An array of Pokémon names.
   */
  public function getPokemonNames() {
    $response = $this->execute('/pokemon', ['limit' => 1500]);
    return $response ? $response['results'] : [];
  }

  /**
   * Fetches Pokémon type data from PokeAPI.
   *
   * @return array
   *   An array of Pokémon type.
   */
  public function getPokemonTypes() {
    $response = $this->execute('/type', ['limit' => 1500]);
    return $response ? $response['results'] : [];
  }

  /**
   * Fetches Pokémon data from PokeAPI.
   *
   * @return array
   *   An array of Pokémon location.
   */
  public function getPokemonLocation() {
    $response = $this->execute('/location', ['limit' => 1500]);
    return $response ? $response['results'] : [];
  }

  /**
   * Fetches Pokemon by ID or Name on Pokeapi endpoint.
   *
   * @param mixed $pokemon
   *   ID or Name of the Pokemon.
   */
  public function getPokemon($pokemon = '') {
    return $this->execute('/pokemon/' . $pokemon);
  }

  /**
   * Retrieves Pokémon data using Name.
   *
   * @param string $name
   *   Name of the Pokemon.
   */
  public function getPokemonByName($name) {
    $name = strtolower($name);
    $name = Html::cleanCssIdentifier($name);
    return $this->getPokemon($name);
  }

  /**
   * Retrieves Pokémon data using ID.
   *
   * @param int $id
   *   ID of the Pokemon.
   */
  public function getPokemonById($id) {
    return $this->getPokemon($id);
  }

  /**
   * Fetches Location Area by ID on Pokeapi endpoint.
   */
  public function getLocationAreaById($id) {
    $response = $this->execute('/location-area/' . $id);
    return $response ? $response['results'] : [];
  }

}
