<?php namespace text\ical;

use lang\partial\Value;
use lang\partial\Builder;

class Event implements Object {
  use Event\is\Value;
  use Event\with\Builder;

  private $organizer, $attendee;
  private $description, $summary, $comment;
  private $dtstart, $dtend, $dtstamp;
  private $uid, $class, $priority, $transp, $sequence, $status;
  private $location;
  private $alarm;

  /** @return text.ical.Attendees */
  public function attendees() { return new Attendees(...(array)$this->attendee); }

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
      'dtstamp'     => $this->dtstamp,
      'uid'         => $this->uid,
      'class'       => $this->class,
      'priority'    => $this->priority,
      'transp'      => $this->transp,
      'location'    => $this->location,
      'status'      => $this->status,
      'sequence'    => $this->sequence,
      'alarm'       => $this->alarm
    ]);
  }
}