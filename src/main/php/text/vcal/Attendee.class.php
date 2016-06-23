<?php namespace text\vcal;

use lang\partial\Value;
use lang\partial\Builder;

class Attendee implements \lang\Value {
  use Attendee\is\Value;
  use Attendee\with\Builder;

  private $role, $partstat, $rsvp, $cn, $value;

  /**
   * Write this object
   *
   * @param  text.vcal.VCalOutput $out
   * @return void
   */
  public function write($out) {
    $out->pair(
      'attendee',
      ['role' => $this->role, 'partstat' => $this->partstat, 'rsvp' => $this->rsvp, 'cn' => $this->cn],
      $this->value
    );
  }
}