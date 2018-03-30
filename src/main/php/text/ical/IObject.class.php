<?php namespace text\ical;

use lang\Value;

interface IObject extends Value {

  /**
   * Write this object
   *
   * @param  text.ical.Output $out
   * @param  string $name
   * @return void
   */
  public function write($out, $name);
}