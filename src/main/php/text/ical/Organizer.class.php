<?php namespace text\ical;

use lang\partial\Value;
use lang\partial\Builder;

class Organizer implements IObject {
  use Organizer\is\Value;
  use Organizer\with\Builder;

  /** @type string */
  private $cn;

  /** @type string */
  private $value;

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