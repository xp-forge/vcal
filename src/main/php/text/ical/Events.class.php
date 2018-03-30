<?php namespace text\ical;

use lang\Value;
use lang\partial\ListOf;

class Events implements Value, \IteratorAggregate {
  use Events\is\ListOf;
}