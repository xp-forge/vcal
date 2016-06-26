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

  #[@test, @values([' ', "\t"])]
  public function continued_line($continuation) {
    $event= (new ICalendar())->read("BEGIN:VEVENT\r\nSUMMARY;LANGUAGE=de-DE:\r\n".$continuation."Test\r\nEND:VEVENT");
    $this->assertEquals('Test', $event->summary()->value());
  }

  #[@test, @values(['\n', '\N'])]
  public function linefeeds_in_data($summary) {
    $event= (new ICalendar())->read("BEGIN:VEVENT\r\nSUMMARY;LANGUAGE=de-DE:".$summary."\r\nEND:VEVENT");
    $this->assertEquals("\n", $event->summary()->value());
  }
}