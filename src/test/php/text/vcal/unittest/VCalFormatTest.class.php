<?php namespace text\vcal\unittest;

use text\vcal\VCalFormat;
use lang\FormatException;

class VCalFormatTest extends \unittest\TestCase {

  #[@test]
  public function can_create() {
    new VCalFormat();
  }

  #[@test, @expect(FormatException::class), @values([
  #  "BEGIN:VCALENDAR",
  #  "BEGIN:VCALENDAR\nBEGIN:VEVENT\nEND:VCALENDAR",
  #  "BEGIN:VCALENDAR\nBEGIN:VEVENT\nEND:VEVENT"
  #])]
  public function unclosed_object($input) {
    (new VCalFormat())->read($input);
  }
}