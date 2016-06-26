<?php namespace text\ical;

interface Object extends \lang\Value {

  /**
   * Write this object
   *
   * @param  text.ical.Output $out
   * @param  string $name
   * @return void
   */
  public abstract function write($out, $name);
}