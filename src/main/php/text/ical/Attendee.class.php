<?php namespace text\ical;

use lang\partial\Value;
use lang\partial\Builder;

class Attendee implements Object {
  use Attendee\is\Value;
  use Attendee\with\Builder;

  private $role, $partstat, $rsvp, $type, $cn, $value;

  /**
   * Write this object
   *
   * @param  text.ical.Output $out
   * @param  string $name
   * @return void
   */
  public function write($out, $name) {
    $out->pair(
      'attendee',
      ['role' => $this->role, 'partstat' => $this->partstat, 'rsvp' => $this->rsvp, 'type' => $this->type, 'cn' => $this->cn],
      $this->value
    );
  }
}