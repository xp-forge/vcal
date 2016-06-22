<?php namespace text\vcal;

use lang\partial\Value;
use lang\partial\Builder;

class Text implements \lang\Value {
  use Text\is\Value;
  use Text\with\Builder;

  private $language, $value;
}