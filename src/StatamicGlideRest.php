<?php

namespace Gioppy\StatamicRestClient;

use GuzzleHttp\Client;

class StatamicGlideRest
{

    use StatamicRestable;

    public function __construct(string $host)
    {
        $this->client = new Client(['base_uri' => $host, 'headers' => ['Content-Type' => 'application/json']]);
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

    public function glide(string $id): self
    {
        $this->query['id'] = $id;
        $this->path = "/assets/glide";
        $this->request();
        return $this;
    }
}