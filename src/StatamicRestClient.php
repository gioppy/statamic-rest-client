<?php

namespace Gioppy\StatamicRestClient;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class StatamicRestClient
{

  private string $endpoint;
  private Client $client;
  private array $query = [];
  private ResponseInterface $response;

  public function __construct(string $host, string $endpoint)
  {
    $this->endpoint = $endpoint;
    $this->client = new Client([ 'base_uri' => $host, 'headers' => [ 'Content-Type' => 'application/json' ] ]);
  }

  public static function make(string $host, string $endpoint)
  {
    return new static($host, $endpoint);
  }

  /**
   * Add fields query parameter
   *
   * @param array $fields Array of fields to include on response
   * @return $this
   */
  public function fields(array $fields): StatamicRestClient
  {
    $this->query['fields'] = implode(',', $fields);
    return $this;
  }

  /**
   * Add filter query parameters
   *
   * @param string $field
   * @param mixed|null $value
   * @param string|null $condition
   * @return $this
   */
  public function where(string $field, mixed $value, string|null $condition = null): StatamicRestClient
  {
    if ($condition) {
      $this->query["filter[{$field}:{$condition}]"] = $value;
    } else {
      $this->query["filter[{$field}]"] = $value;
    }
    return $this;
  }

  /**
   * Add site query parameter
   *
   * @param string $site
   * @return $this
   */
  public function whereSite(string $site): StatamicRestClient
  {
    $this->query['site'] = $site;
    return $this;
  }

  /**
   * Add sort query parameter
   *
   * @param array $fields
   * @return $this
   */
  public function sort(array $fields): StatamicRestClient
  {
    $this->query['sort'] = implode(',', $fields);
    return $this;
  }

  /**
   * Get all entries from collection
   *
   * @param string $collection
   * @return $this
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function collection(string $collection): StatamicRestClient
  {
    $this->response = $this->client->get("/{$this->endpoint}/collections/{$collection}/entries", [
      'query' => $this->query,
    ]);

    return $this;
  }

  /**
   * Get single collection entry
   *
   * @param string $collection
   * @param string $id
   * @return $this
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function entry(string $collection, string $id): StatamicRestClient
  {
    $this->response = $this->client->get("/{$this->endpoint}/collections/{$collection}/entries/{$id}", [
      'query' => $this->query,
    ]);
    return $this;
  }

  /**
   * Get all assets from container
   *
   * @param string $container
   * @return $this
   * @throws \GuzzleHttp\Exception\GuzzleException\
   */
  public function assets(string $container): StatamicRestClient
  {
    $this->response = $this->client->get("/{$this->endpoint}/assets/{$container}", [
      'query' => $this->query,
    ]);
    return $this;
  }

  /**
   * Get single asset
   *
   * @param string $container
   * @param string $path
   * @return $this
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function asset(string $container, string $path): StatamicRestClient
  {
    $this->response = $this->client->get("/{$this->endpoint}/assets/{$container}/{$path}", [
      'query' => $this->query,
    ]);
    return $this;
  }

  /**
   * Get single asset from its id
   *
   * @param string $id Id of asset, in the form of container::id
   * @return $this
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function assetById(string $id): StatamicRestClient
  {
    return $this->asset(...explode('::', $id));
  }

  /**
   * Get data node from response
   * @return array
   */
  public function data(): array
  {
    return (array) json_decode($this->response->getBody()->getContents())->data;
  }

}