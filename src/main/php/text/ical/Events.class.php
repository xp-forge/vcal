<?php namespace text\ical;

use lang\partial\ListOf;

class Events implements \lang\Value, \IteratorAggregate {
  use Events\is\ListOf;
}