<?php namespace text\ical\unittest;

use text\ical\ICalendar;
use lang\FormatException;
use io\streams\MemoryOutputStream;

class ICalendarTest extends \unittest\TestCase {

  /** @return php.Generator */
  private function fixtures() {
    foreach (Fixtures::values() as $fixture) {
      yield [$fixture];
    }
  }

  #[@test]
  public function can_create() {
    new ICalendar();
  }

  #[@test, @values('fixtures')]
  public function read($fixture) {
    $this->assertEquals($fixture->object(), (new ICalendar())->read($fixture->string()));
  }

  #[@test, @values('fixtures')]
  public function write($fixture) {
    $out= new MemoryOutputStream();
    (new ICalendar())->write($fixture->object(), $out);

    $this->assertEquals($fixture->string(), trim($out->getBytes()));
  }

  #[@test, @expect(FormatException::class), @values([
  #  "BEGIN:VCALENDAR",
  #  "BEGIN:VCALENDAR\nBEGIN:VEVENT\nEND:VCALENDAR",
  #  "BEGIN:VCALENDAR\nBEGIN:VEVENT\nEND:VEVENT"
  #])]
  public function unclosed_object($input) {
    (new ICalendar())->read($input);
  }

  #[@test, @expect(
  #  class= FormatException::class,
  #  withMessage= 'Unknown object type "vevent" at root level'
  #)]
  public function root_object_must_be_calendar() {
    (new ICalendar())->read("BEGIN:VEVENT");
  }

  #[@test, @expect(
  #  class= FormatException::class,
  #  withMessage= 'Unknown object type "vcalendar" inside "vcalendar"'
  #)]
  public function cannot_nest_calendard() {
    (new ICalendar())->read("BEGIN:VCALENDAR\r\nBEGIN:VCALENDAR");
  }

  #[@test, @expect(
  #  class= FormatException::class,
  #  withMessage= 'Unknown object type "unknown" inside "vcalendar"'
  #)]
  public function unknown_object_inside_calendar() {
    (new ICalendar())->read("BEGIN:VCALENDAR\r\nBEGIN:UNKNOWN");
  }

  #[@test, @expect(
  #  class= FormatException::class,
  #  withMessage= 'Unknown object type "unknown" inside "vcalendar/vevent"'
  #)]
  public function unknown_object_inside_calendar_event() {
    (new ICalendar())->read("BEGIN:VCALENDAR\r\nBEGIN:VEVENT\r\nBEGIN:UNKNOWN");
  }

  #[@test, @values([' ', "\t"])]
  public function continued_line($continuation) {
    $calendar= (new ICalendar())->read(
      "BEGIN:VCALENDAR\r\n".
      "BEGIN:VEVENT\r\n".
      "SUMMARY;LANGUAGE=de-DE:\r\n".$continuation."Test\r\n".
      "END:VEVENT\r\n".
      "END:VCALENDAR"
    );
    $this->assertEquals('Test', $calendar->event()->summary()->value());
  }

  #[@test, @values(['\n', '\N'])]
  public function linefeeds_in_data($summary) {
    $calendar= (new ICalendar())->read(
      "BEGIN:VCALENDAR\r\n".
      "BEGIN:VEVENT\r\n".
      "SUMMARY;LANGUAGE=de-DE:".$summary."\r\n".
      "END:VEVENT\r\n".
      "END:VCALENDAR"
    );
    $this->assertEquals("\n", $calendar->event()->summary()->value());
  }
}