<?php namespace text\ical;

use lang\partial\Value;
use lang\partial\Builder;

class Event implements IObject {
  use Event\is\Value;
  use Event\with\Builder;
  use Properties;

  /** @type text.ical.Organizer */
  private $organizer;

  /** @type text.ical.Attendee[] */
  private $attendees;

  /** @type text.ical.Text */
  private $description;

  /** @type text.ical.Text */
  private $summary;

  /** @type text.ical.Text */
  private $comment;

  /** @type text.ical.Date */
  private $dtstart;

  /** @type text.ical.Date */
  private $dtend;

  /** @type text.ical.Date */
  private $dtstamp;

  /** @type string */
  private $uid;

  /** @type string */
  private $class;

  /** @type string */
  private $priority;

  /** @type string */
  private $transp;

  /** @type string */
  private $sequence;

  /** @type string */
  private $status;

  /** @type text.ical.Text */
  private $location;

  /** @type text.ical.Alarm */
  private $alarm;

  /** @type [:string] */
  private $properties;

  /** @return text.ical.Attendees */
  public function attendees() { return new Attendees(...(array)$this->attendees); }

  /**
   * Write this object
   *
   * @param  text.ical.Output $out
   * @param  string $name
   * @return void
   */
  public function write($out, $name) {
    $out->object('vevent', $this->merge([
      'organizer'   => $this->organizer,
      'attendee'    => $this->attendees,
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
    ]));
  }
}