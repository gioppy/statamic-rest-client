<?php

namespace Gioppy\StatamicRestClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;

trait StatamicRestable
{

  private string $endpoint;
  private Client $client;
  private string $path;
  private array $query = [];
  private ResponseInterface $response;

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