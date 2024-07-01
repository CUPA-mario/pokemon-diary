<?php

namespace Drupal\Pokeapi\Controller;

use \Drupal\Core\Controller\ControllerBase;
use GuzzleHttp\ClientInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;

class PokemonAPIController extends ControllerBase {

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

    private $url = 'https://pokeapi.co/api/v2';

    /**
     * Constructs a new PokemonService object.
     *
     * @param \GuzzleHttp\ClientInterface $http_client
     *   The Guzzle HTTP client.
     * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
     *   The logger factory.
     */
    public function __construct(ClientInterface $http_client, LoggerChannelFactoryInterface $logger_factory)
    {
        $this->httpClient = $http_client;
        $this->loggerFactory = $logger_factory;
    }

    /**
     * Fetch connection on Pokeapi endpoint.
     */
    public function checkConnection() {
        try {
            $response = $this->httpClient->request('GET', $this->url);
            $status = $response->getStatusCode();
            if($status == '200') {
                return $status;
            }
        }
        catch (\Exception $e) {
            $this->loggerFactory->get('pokemonapi')->error('Error connecting Pokémon API: @message', 
            ['@message' => $e->getMessage()]);
        }
    }

    public function get(string $method = 'GET', string $path, array $query = []) {
        $this->httpClient->request($method, $this->url . '/' .  $path, [ 'query' => $query,
        ]);
    }

    public function getPokemon($query = []) {
        $response = $this->get('GET', 'pokemon', $query);
        return $response;     
    }

    public function getLocationArea($query = []) {
        $response = $this->get('GET', 'location-area', $query);
        return $response;     
    }

    public function getPokemonById($id) {
        $response = $this->get('GET', 'pokemon/' . $id);
        return $response;     
    }

    public function getLocationAreaById($id) {
        $response = $this->get('GET', 'location-area/' . $id);
        return $response;     
    }

    public function content() {
        $build = [
            '#markup' => $this->getLocationArea(),
        ];
        return $build;
    }
}