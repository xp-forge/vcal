<?php namespace text\ical;

use lang\partial\{Builder, Value};

class Date implements IObject {
  use Date\is\Value;
  use Date\with\Builder;

  /** @type string */
  private $tzid;

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
    $out->pair($name, ['tzid' => $this->tzid], $this->value);
  }
}