<?php namespace text\vcal;

use lang\partial\Value;
use lang\partial\Builder;

class Organizer implements \lang\Value {
  use Organizer\is\Value;
  use Organizer\with\Builder;

  private $cn, $value;
}