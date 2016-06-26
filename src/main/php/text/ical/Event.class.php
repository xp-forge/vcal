<?php namespace text\ical;

use lang\partial\Value;
use lang\partial\Builder;

class Event implements Object {
  use Event\is\Value;
  use Event\with\Builder;

  private $organizer, $attendee, $description, $summary, $comment, $dtstart, $dtend, $uid, $class, $location, $status, $alarm;

  /**
   * Write this object
   *
   * @param  text.ical.Output $out
   * @param  string $name
   * @return void
   */
  public function write($out, $name) {
    $out->object('vevent', [
      'organizer'   => $this->organizer,
      'attendee'    => $this->attendee,
      'description' => $this->description,
      'comment'     => $this->comment,
      'summary'     => $this->summary,
      'dtstart'     => $this->dtstart,
      'dtend'       => $this->dtend,
      'uid'         => $this->uid,
      'class'       => $this->class,
      'location'    => $this->location,
      'status'      => $this->status,
      'alarm'       => $this->alarm
    ]);
  }
}