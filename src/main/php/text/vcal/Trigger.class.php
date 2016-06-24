<?php namespace text\vcal;

use lang\partial\Value;
use lang\partial\Builder;

class Trigger implements \lang\Value {
  use Trigger\is\Value;
  use Trigger\with\Builder;

  private $related, $value;

  /**
   * Write this object
   *
   * @param  text.vcal.VCalOutput $out
   * @param  string $name
   * @return void
   */
  public function write($out, $name) {
    $out->pair($name, ['related' => $this->related], $this->value);
  }
}