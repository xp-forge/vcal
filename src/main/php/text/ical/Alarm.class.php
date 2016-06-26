<?php namespace text\ical;

use lang\partial\Value;
use lang\partial\Builder;

class Alarm implements Object {
  use Alarm\is\Value;
  use Alarm\with\Builder;

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
    $out->object('valarm', array_merge(
      [
        'description' => $this->description,
        'trigger'     => $this->trigger,
        'action'      => $this->action
      ],
      (array)$this->properties
    ));
  }
}