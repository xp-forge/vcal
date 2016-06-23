<?php namespace text\vcal\unittest;

use text\vcal\VCalFormat;
use text\vcal\Calendar;
use text\vcal\Event;
use text\vcal\Organizer;
use text\vcal\Attendee;
use text\vcal\Text;
use text\vcal\Date;
use lang\FormatException;
use io\streams\MemoryOutputStream;

class VCalFormatWritingTest extends \unittest\TestCase {

  private function assertVCal($expected, $object) {
    $out= new MemoryOutputStream();
    (new VCalFormat())->write($object, $out);

    $this->assertEquals(trim(preg_replace("/\n\s+/", "\n", $expected)), trim($out->getBytes()));
  }

  #[@test]
  public function empty_request() {
    $expected= '
      BEGIN:VCALENDAR
      METHOD:REQUEST
      PRODID:Microsoft Exchange Server 2010
      VERSION:2.0
      END:VCALENDAR
    ';

    $this->assertVCal($expected, Calendar::with()
      ->method('REQUEST')
      ->prodid('Microsoft Exchange Server 2010')
      ->version('2.0')
      ->event(null)
      ->create()
    );
  }

  #[@test]
  public function request_with_event() {
    $expected= '
      BEGIN:VEVENT
      ORGANIZER;CN=The Organizer:MAILTO:organizer@example.com
      ATTENDEE;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE;CN=The Attendee 1:MAILTO:attendee1@example.com
      ATTENDEE;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE;CN=The Attendee 2:MAILTO:attendee2@example.com
      ATTENDEE;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE;CN=The Attendee 3:MAILTO:attendee3@example.com
      DTSTART;TZID=W. Europe Standard Time:20160524T183000
      DTEND;TZID=W. Europe Standard Time:20160524T190000
      LOCATION;LANGUAGE=de-DE:BS 50 EG 0102
      END:VEVENT
    ';

    $this->assertVCal($expected, Event::with()
      ->organizer(Organizer::with()->cn('The Organizer')->value('MAILTO:organizer@example.com')->create())
      ->attendee([
        Attendee::with()
          ->role('REQ-PARTICIPANT')
          ->partstat('NEEDS-ACTION')
          ->rsvp('TRUE')
          ->cn('The Attendee 1')
          ->value('MAILTO:attendee1@example.com')
          ->create()
        ,
        Attendee::with()
          ->role('REQ-PARTICIPANT')
          ->partstat('NEEDS-ACTION')
          ->rsvp('TRUE')
          ->cn('The Attendee 2')
          ->value('MAILTO:attendee2@example.com')
          ->create()
        ,
        Attendee::with()
          ->role('REQ-PARTICIPANT')
          ->partstat('NEEDS-ACTION')
          ->rsvp('TRUE')
          ->cn('The Attendee 3')
          ->value('MAILTO:attendee3@example.com')
          ->create()
      ])
      ->dtstart(new Date('W. Europe Standard Time', '20160524T183000'))
      ->dtend(new Date('W. Europe Standard Time', '20160524T190000'))
      ->location(new Text('de-DE', 'BS 50 EG 0102'))
      ->create()
    );
  }
}