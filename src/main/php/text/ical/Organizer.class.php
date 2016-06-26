<?php namespace text\ical;

use lang\partial\Value;
use lang\partial\Builder;

class Organizer implements Object {
  use Organizer\is\Value;
  use Organizer\with\Builder;

  private $cn, $value;

  /**
   * Write this object
   *
   * @param  text.ical.Output $out
   * @param  string $name
   * @return void
   */
  public function write($out, $name) {
    $out->pair('organizer', ['cn' => $this->cn], $this->value);
  }
}