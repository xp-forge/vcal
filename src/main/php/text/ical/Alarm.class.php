<?php namespace text\ical;

use lang\partial\Value;
use lang\partial\Builder;

class Alarm implements Object {
  use Alarm\is\Value;
  use Alarm\with\Builder;
  use Properties;

  private $description, $trigger, $action, $properties;

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