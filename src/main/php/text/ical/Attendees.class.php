<?php namespace text\ical;

use lang\Value;
use lang\partial\ListOf;

class Attendees implements Value, \IteratorAggregate {
  use Attendees\is\ListOf;
}