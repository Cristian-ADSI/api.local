<?php

namespace Services\RestService\Interfaces;

interface ConfigurationServiceInterface
{
  public function get(string $key, $default = null);
}
