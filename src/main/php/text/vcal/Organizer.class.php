<?php namespace text\vcal;

use lang\partial\Value;
use lang\partial\Builder;

class Organizer implements \lang\Value {
  use Organizer\is\Value;
  use Organizer\with\Builder;

  private $cn, $value;

  /**
   * Write this object
   *
   * @param  text.vcal.VCalOutput $out
   * @return void
   */
  public function write($out) {
    $out->pair('organizer', ['cn' => $this->cn], $this->value);
  }
}