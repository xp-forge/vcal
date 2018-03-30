<?php namespace text\ical;

use lang\partial\Value;
use lang\partial\Builder;

class Alarm implements IObject {
  use Alarm\is\Value;
  use Alarm\with\Builder;
  use Properties;

  /** @type string */
  private $description;

  /** @type text.ical.Trigger */
  private $trigger;

  /** @type string */
  private $action;

  /** @type [:string] */
  private $properties;

  /**
   * Write this object
   *
   * @param  text.ical.Output $out
   * @param  string $name
   * @return void
   */
  public function write($out, $name) {
    $out->object('valarm', $this->merge([
      'description' => $this->description,
      'trigger'     => $this->trigger,
      'action'      => $this->action
    ]));
  }
}