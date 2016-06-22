<?php namespace text\vcal;

use lang\partial\Value;
use lang\partial\Builder;

class Event implements \lang\Value {
  use Event\is\Value;
  use Event\with\Builder;

  private $organizer, $attendee, $description, $summary, $dtstart, $dtend, $location;
}