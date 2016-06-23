<?php namespace text\vcal;

use lang\partial\Value;
use lang\partial\Builder;

class Event implements \lang\Value {
  use Event\is\Value;
  use Event\with\Builder;

  private $organizer, $attendee, $description, $summary, $dtstart, $dtend, $location;

  /**
   * Write this object
   *
   * @param  text.vcal.VCalOutput $out
   * @param  string $name
   * @return void
   */
  public function write($out, $name) {
    $out->object('vevent', [
      'organizer'   => $this->organizer,
      'attendee'    => $this->attendee,
      'description' => $this->description,
      'summary'     => $this->summary,
      'dtstart'     => $this->dtstart,
      'dtend'       => $this->dtend,
      'location'    => $this->location
    ]);
  }
}