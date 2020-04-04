<?php namespace text\ical;

use lang\partial\Builder;
use lang\partial\Value;

class Text implements IObject {
  use Text\is\Value;
  use Text\with\Builder;

  private static $ESCAPING = ["\n" => '\n', ',' => '\,', ';' => '\;', '\\' => '\\\\'];

  /** @type string */
  private $language;

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
    $out->pair($name, ['language' => $this->language], strtr($this->value, self::$ESCAPING));
  }
}