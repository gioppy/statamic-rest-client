<?php

namespace Gioppy\StatamicRestClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;

class StatamicRestClient
{

  private string $endpoint;
  private Client $client;
  private string $path;
  private array $query = [];
  private ResponseInterface $response;

  public function __construct(string $host, string $endpoint = 'api')
  {
    $this->endpoint = $endpoint;
    $this->client = new Client([ 'base_uri' => $host, 'headers' => [ 'Content-Type' => 'application/json' ] ]);
  }

  public static function make(string $host, string $endpoint = 'api')
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

  public function dump(): StatamicRestClient
  {
    dump(['path' => $this->path, 'query' => $this->query]);
    return $this;
  }

  /**
   * Add limit and page query parameters
   *
   * @param int $limit Number of items
   * @param int|null $page Page to load; leave empty to load first page
   * @return $this
   */
  public function paginate(int $limit, int $page = null): StatamicRestClient
  {
    $this->query['limit'] = $limit;
    if ($page) {
      $this->query['page'] = $page;
    }
    return $this;
  }

  /**
   * Get all entries from collection
   *
   * @param string $collection
   * @return $this
   * @throws GuzzleException
   */
  public function entries(string $collection): StatamicRestClient
  {
    $this->path = "/{$this->endpoint}/collections/{$collection}/entries";
    $this->request();
    return $this;
  }

  /**
   * Get single collection entry
   *
   * @param string $collection
   * @param string $id
   * @return $this
   * @throws GuzzleException
   */
  public function entry(string $collection, string $id): StatamicRestClient
  {
    $this->path = "/{$this->endpoint}/collections/{$collection}/entries/{$id}";
    $this->request();
    return $this;
  }

  /**
   * Get navigation tree items
   *
   * @param string $name
   * @return $this
   * @throws GuzzleException
   */
  public function navigation(string $name): StatamicRestClient
  {
    $this->path = "/{$this->endpoint}/navs/{$name}/tree";
    $this->request();
    return $this;
  }

  /**
   * Get terms of a taxonomy
   *
   * @param string $taxonomy
   * @return $this
   * @throws GuzzleException
   */
  public function terms(string $taxonomy): StatamicRestClient
  {
    $this->path = "/{$this->endpoint}/taxonomies/{$taxonomy}/terms";
    $this->request();
    return $this;
  }

  /**
   * Get single term of taxonomy
   *
   * @param string $taxonomy
   * @param string $slug
   * @return $this
   * @throws GuzzleException
   */
  public function term(string $taxonomy, string $slug): StatamicRestClient
  {
    $this->path = "/{$this->endpoint}/taxonomies/{$taxonomy}/terms/{$slug}";
    $this->request();
    return $this;
  }

  /**
   * Get all globals
   *
   * @return $this
   * @throws GuzzleException
   */
  public function globals(): StatamicRestClient
  {
    $this->path = "/{$this->endpoint}/globals";
    $this->request();
    return $this;
  }

  /**
   * Get single global
   *
   * @param string $handle
   * @return $this
   * @throws GuzzleException
   */
  public function global(string $handle): StatamicRestClient
  {
    $this->path = "/{$this->endpoint}/globals/{$handle}";
    $this->request();
    return $this;
  }

  /**
   * Get all assets from container
   *
   * @param string $container
   * @return $this
   * @throws GuzzleException
   */
  public function assets(string $container): StatamicRestClient
  {
    $this->path = "/{$this->endpoint}/assets/{$container}";
    $this->request();
    return $this;
  }

  /**
   * Get single asset
   *
   * @param string $container
   * @param string $path
   * @return $this
   * @throws GuzzleException
   */
  public function asset(string $container, string $path): StatamicRestClient
  {
    $this->path = "/{$this->endpoint}/assets/{$container}/{$path}";
    $this->request();
    return $this;
  }

  /**
   * Get single asset from its id
   *
   * @param string $id Id of asset, in the form of container::id
   * @return $this
   * @throws GuzzleException
   */
  public function assetById(string $id): StatamicRestClient
  {
    return $this->asset(...explode('::', $id));
  }

  /**
   * Return entire response
   *
   * @return array
   */
  public function all(): array
  {
    return (array) json_decode($this->response->getBody()->getContents());
  }

  /**
   * Get data node from response
   *
   * @return array
   */
  public function data(): array
  {
    return (array) json_decode($this->response->getBody()->getContents())->data;
  }

  /**
   * Return data node from response as Collection
   *
   * @return Collection
   */
  public function toCollection(): Collection
  {
    return collect(json_decode($this->response->getBody()->getContents())->data);
  }

  /**
   * Execute a request based on path
   *
   * @return void
   * @throws GuzzleException
   */
  protected function request(): void
  {
    $this->response = $this->client->get($this->path, [
      'query' => $this->query,
    ]);
  }

}