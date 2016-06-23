<?php namespace text\vcal\unittest;

use text\vcal\VCalFormat;
use lang\FormatException;
use io\streams\MemoryOutputStream;

class VCalFormatTest extends \unittest\TestCase {

  /** @return php.Generator */
  private function fixtures() {
    foreach (Fixtures::values() as $fixture) {
      yield [$fixture];
    }
  }

  #[@test]
  public function can_create() {
    new VCalFormat();
  }

  #[@test, @values('fixtures')]
  public function read($fixture) {
    $this->assertEquals($fixture->object(), (new VCalFormat())->read($fixture->string()));
  }

  #[@test, @values('fixtures')]
  public function write($fixture) {
    $out= new MemoryOutputStream();
    (new VCalFormat())->write($fixture->object(), $out);

    $this->assertEquals($fixture->string(), trim($out->getBytes()));
  }

  #[@test, @expect(FormatException::class), @values([
  #  "BEGIN:VCALENDAR",
  #  "BEGIN:VCALENDAR\nBEGIN:VEVENT\nEND:VCALENDAR",
  #  "BEGIN:VCALENDAR\nBEGIN:VEVENT\nEND:VEVENT"
  #])]
  public function unclosed_object($input) {
    (new VCalFormat())->read($input);
  }

  #[@test]
  public function continued_line() {
    $event= (new VCalFormat())->read(
      "BEGIN:VEVENT\r\n".
      "ATTENDEE;ROLE=CHAIR;PARTSTAT=ACCEPTED;\r\n".
      " CN=\"participant\";\r\n".
      " RSVP=FALSE:mailto:participant@example.com\r\n".
      "END:VEVENT"
    );
    $this->assertEquals('mailto:participant@example.com', $event->attendee()->value());
  }
}