<?php namespace text\ical;

use lang\partial\ListOf;

class Attendees implements \lang\Value, \IteratorAggregate {
  use Attendees\is\ListOf;
}