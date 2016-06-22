<?php namespace text\vcal;

use lang\partial\Value;
use lang\partial\Builder;

class Calendar implements \lang\Value {
  use Calendar\is\Value;
  use Calendar\with\Builder;

  private $method, $prodid, $version, $event;
}