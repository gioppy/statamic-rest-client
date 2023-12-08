<?php

namespace Gioppy\StatamicRestClient;

use GuzzleHttp\Client;

class StatamicGlideRest
{

  use StatamicRestable;

  public function __construct(string $host)
  {
    $this->client = new Client([ 'base_uri' => $host, 'headers' => [ 'Content-Type' => 'application/json' ] ]);
  }

  public static function make(string $host): static
  {
    return new static($host);
  }

  /**
   * Add presets query parameter.
   * Only useful in combination with statamic-glide-rest
   *
   * @param array $presets
   * @return $this
   */
  public function presets(array $presets): self
  {
    $this->query['presets'] = implode(',', $presets);
    return $this;
  }

  public function glide(string $container, string $path): self
  {
    $this->path = "/assets/glide/{$container}/{$path}";
    $this->request();
    return $this;
  }
}