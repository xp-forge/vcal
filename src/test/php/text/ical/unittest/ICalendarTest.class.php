<?php namespace text\ical\unittest;

use io\streams\MemoryOutputStream;
use lang\{ElementNotFoundException, FormatException};
use text\ical\ICalendar;
use unittest\{Expect, Test, Values};

class ICalendarTest extends \unittest\TestCase {

  /** @return iterable */
  private function fixtures() {
    foreach (Fixtures::values() as $fixture) {
      yield [$fixture];
    }
  }

  #[Test]
  public function can_create() {
    new ICalendar();
  }

  #[Test, Values('fixtures')]
  public function read($fixture) {
    $this->assertEquals($fixture->object(), (new ICalendar())->read($fixture->string()));
  }

  #[Test, Values('fixtures')]
  public function write($fixture) {
    $out= new MemoryOutputStream();
    (new ICalendar())->write($fixture->object(), $out);

    $this->assertEquals($fixture->string(), trim($out->getBytes()));
  }

  #[Test, Expect(FormatException::class), Values(["BEGIN:VCALENDAR", "BEGIN:VCALENDAR\nBEGIN:VEVENT\nEND:VCALENDAR", "BEGIN:VCALENDAR\nBEGIN:VEVENT\nEND:VEVENT"])]
  public function unclosed_object($input) {
    (new ICalendar())->read($input);
  }

  #[Test, Expect(class: FormatException::class, withMessage: 'No object type at root level')]
  public function empty_input_raises_exception() {
    (new ICalendar())->read("");
  }

  #[Test, Expect(class: FormatException::class, withMessage: 'No object type at root level')]
  public function property_at_root_level_raises_exception() {
    (new ICalendar())->read("SUMMARY;LANGUAGE=de-DE:Test 1");
  }

  #[Test, Expect(class: FormatException::class, withMessage: 'Unknown object type "event" at root level')]
  public function root_object_must_be_calendar() {
    (new ICalendar())->read("BEGIN:VEVENT");
  }

  #[Test, Expect(class: FormatException::class, withMessage: 'Unknown object type "calendar" inside "calendar"')]
  public function cannot_nest_calendars() {
    (new ICalendar())->read("BEGIN:VCALENDAR\r\nBEGIN:VCALENDAR");
  }

  #[Test, Expect(class: FormatException::class, withMessage: 'Unknown object type "unknown" inside "calendar"')]
  public function unknown_object_inside_calendar() {
    (new ICalendar())->read("BEGIN:VCALENDAR\r\nBEGIN:UNKNOWN");
  }

  #[Test, Expect(class: FormatException::class, withMessage: 'Unknown object type "unknown" inside "event"')]
  public function unknown_object_inside_calendar_event() {
    (new ICalendar())->read("BEGIN:VCALENDAR\r\nBEGIN:VEVENT\r\nBEGIN:UNKNOWN");
  }

  #[Test, Expect(class: FormatException::class, withMessage: 'Illegal nesting of "unknown" inside "calendar"')]
  public function illegal_nesting() {
    (new ICalendar())->read("BEGIN:VCALENDAR\r\nEND:UNKNOWN");
  }

  #[Test, Expect(class: FormatException::class, withMessage: 'Illegal nesting of "calendar" at root level')]
  public function end_before_begin() {
    (new ICalendar())->read("END:VCALENDAR");
  }

  #[Test]
  public function no_events_present() {
    $calendar= (new ICalendar())->read("BEGIN:VCALENDAR\r\nEND:VCALENDAR");
    $this->assertFalse($calendar->events()->present());
  }

  #[Test, Expect(ElementNotFoundException::class)]
  public function no_events_first() {
    $calendar= (new ICalendar())->read("BEGIN:VCALENDAR\r\nEND:VCALENDAR");
    $this->assertFalse($calendar->events()->first());
  }

  #[Test]
  public function all_events_present() {
    $calendar= (new ICalendar())->read(
      "BEGIN:VCALENDAR\r\n".
      "BEGIN:VEVENT\r\n".
      "SUMMARY;LANGUAGE=de-DE:Test\r\n".
      "END:VEVENT\r\n".
      "END:VCALENDAR"
    );
    $this->assertTrue($calendar->events()->present());
  }

  #[Test]
  public function iterate_events() {
    $calendar= (new ICalendar())->read(
      "BEGIN:VCALENDAR\r\n".
      "BEGIN:VEVENT\r\n".
      "SUMMARY;LANGUAGE=de-DE:Test 1\r\n".
      "END:VEVENT\r\n".
      "BEGIN:VEVENT\r\n".
      "SUMMARY;LANGUAGE=de-DE:Test 2\r\n".
      "END:VEVENT\r\n".
      "END:VCALENDAR"
    );
    $this->assertEquals(['Test 1', 'Test 2'], array_map(
      function($event) { return $event->summary()->value(); },
      iterator_to_array($calendar->events())
    ));
  }

  #[Test]
  public function property_named() {
    $calendar= (new ICalendar())->read(
      "BEGIN:VCALENDAR\r\n".
      "BEGIN:VEVENT\r\n".
      "X-MICROSOFT-DISALLOW-COUNTER:FALSE\r\n".
      "END:VEVENT\r\n".
      "END:VCALENDAR"
    );
    $this->assertEquals('FALSE', $calendar->events()->first()->property('X-MICROSOFT-DISALLOW-COUNTER'));
  }

  #[Test]
  public function utc_date() {
    $calendar= (new ICalendar())->read(
      "BEGIN:VCALENDAR\r\n".
      "BEGIN:VEVENT\r\n".
      "DTSTART:19970714T173000Z\r\n".
      "END:VEVENT\r\n".
      "END:VCALENDAR"
    );
    $this->assertEquals('19970714T173000Z', $calendar->events()->first()->dtstart()->value());
  }

  #[Test, Values([' ', "\t"])]
  public function continued_line($continuation) {
    $calendar= (new ICalendar())->read(
      "BEGIN:VCALENDAR\r\n".
      "BEGIN:VEVENT\r\n".
      "SUMMARY;LANGUAGE=de-DE:\r\n".$continuation."Test\r\n".
      "END:VEVENT\r\n".
      "END:VCALENDAR"
    );
    $this->assertEquals('Test', $calendar->events()->first()->summary()->value());
  }

  #[Test, Values(['\n', '\N'])]
  public function linefeeds_in_data($summary) {
    $calendar= (new ICalendar())->read(
      "BEGIN:VCALENDAR\r\n".
      "BEGIN:VEVENT\r\n".
      "SUMMARY;LANGUAGE=de-DE:".$summary."\r\n".
      "END:VEVENT\r\n".
      "END:VCALENDAR"
    );
    $this->assertEquals("\n", $calendar->events()->first()->summary()->value());
  }

  #[Test]
  public function escaping() {
    $calendar= (new ICalendar())->read(
      "BEGIN:VCALENDAR\r\n".
      "BEGIN:VEVENT\r\n".
      "SUMMARY;LANGUAGE=de-DE:BS50\, 1303\; coolest room\\\\on earth\\N\r\n".
      "END:VEVENT\r\n".
      "END:VCALENDAR"
    );
    $this->assertEquals("BS50, 1303; coolest room\\on earth\n", $calendar->events()->first()->summary()->value());
  }
}