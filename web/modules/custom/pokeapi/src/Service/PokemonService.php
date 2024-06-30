<?php

namespace Drupal\pokeapi\Service;

use GuzzleHttp\ClientInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;

/**
 * Service to fetch Pokémon data.
 */
class PokemonService
{

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
    public function __construct(ClientInterface $http_client, LoggerChannelFactoryInterface $logger_factory)
    {
        $this->httpClient = $http_client;
        $this->loggerFactory = $logger_factory;
    }

    private function execute($path, $query = [])
    {
        try {
            $response = $this->httpClient->request('GET', 'https://pokeapi.co/api/v2' . $path, [
                'query' => $query,
            ]);
            return json_decode($response->getBody()->getContents(), TRUE);
        } catch (\Exception $e) {
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
    public function getPokemonNames()
    {
        $response = $this->execute('/pokemon', ['limit' => 1500]);
        return $response ? $response['results'] : [];
    }

    /**
     * Fetches Pokémon data from PokeAPI.
     *
     * @return array
     *   An array of Pokémon location.
     */
    public function getPokemonLocation()
    {
        $response = $this->execute('/location', ['limit' => 1500]);
        return $response ? $response['results'] : [];
    }
}
