<?php

namespace Gioppy\StatamicRestClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class StatamicRestClient
{

  use StatamicRestable;

  public function __construct(string $host, string $endpoint = 'api')
  {
    $this->endpoint = $endpoint;
    $this->client = new Client([ 'base_uri' => $host, 'headers' => [ 'Content-Type' => 'application/json' ] ]);
  }

  public static function make(string $host, string $endpoint = 'api'): static
  {
    return new static($host, $endpoint);
  }

  /**
   * Add fields query parameter
   *
   * @param array $fields Array of fields to include on response
   * @return $this
   */
  public function fields(array $fields): self
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
  public function where(string $field, mixed $value, string|null $condition = null): self
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
  public function whereSite(string $site): self
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
  public function sort(array $fields): self
  {
    $this->query['sort'] = implode(',', $fields);
    return $this;
  }

  public function dump(): self
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
  public function paginate(int $limit, int $page = null): self
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
  public function entries(string $collection): self
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
  public function entry(string $collection, string $id): self
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
  public function navigation(string $name): self
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
  public function terms(string $taxonomy): self
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
  public function term(string $taxonomy, string $slug): self
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
  public function globals(): self
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
  public function global(string $handle): self
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
  public function assets(string $container): self
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
  public function asset(string $container, string $path): self
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
  public function assetById(string $id): self
  {
    return $this->asset(...explode('::', $id));
  }

}