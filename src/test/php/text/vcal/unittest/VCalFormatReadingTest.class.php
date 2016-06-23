<?php namespace text\vcal\unittest;

use text\vcal\VCalFormat;
use text\vcal\Calendar;
use text\vcal\Event;
use text\vcal\Organizer;
use text\vcal\Attendee;
use text\vcal\Text;
use text\vcal\Date;
use text\vcal\TimeZone;
use text\vcal\TimeZoneInfo;

class VCalFormatReadingTest extends \unittest\TestCase {

  private function read($string) {
    return (new VCalFormat())->read(trim(preg_replace("/\n\s+/", "\n", $string)));
  }

  #[@test]
  public function calendar() {
    $calendar= $this->read('
      BEGIN:VCALENDAR
      METHOD:REQUEST
      PRODID:Microsoft Exchange Server 2010
      VERSION:2.0
      END:VCALENDAR
    ');

    $this->assertEquals(
      Calendar::with()
        ->method('REQUEST')
        ->prodid('Microsoft Exchange Server 2010')
        ->version('2.0')
        ->event(null)
        ->create()
      ,
      $calendar
    );
  }

  #[@test]
  public function event() {
    $event= $this->read('
      BEGIN:VEVENT
      ORGANIZER;CN=The Organizer:MAILTO:organizer@example.com
      ATTENDEE;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE;CN=The Attendee 1:MAILTO:attendee1@example.com
      ATTENDEE;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE;CN=The Attendee 2:MAILTO:attendee2@example.com
      ATTENDEE;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE;CN=The Attendee 3:MAILTO:attendee3@example.com
      DTSTART;TZID=W. Europe Standard Time:20160524T183000
      DTEND;TZID=W. Europe Standard Time:20160524T190000
      LOCATION;LANGUAGE=de-DE:BS 50 EG 0102
      SUMMARY;LANGUAGE=de-DE:Treffen
      COMMENT;LANGUAGE=de-DE:\n
      END:VEVENT
    ');

    $this->assertEquals(
      Event::with()
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
        ->comment(new Text('de-DE', "\n"))
        ->summary(new Text('de-DE', 'Treffen'))
        ->create()
      ,
      $event
    );
  }

  #[@test]
  public function timezone() {
    $timezone= $this->read('
      BEGIN:VTIMEZONE
      TZID:W. Europe Standard Time
      BEGIN:STANDARD
      DTSTART:16010101T030000
      TZOFFSETFROM:+0200
      TZOFFSETTO:+0100
      RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=-1SU;BYMONTH=10
      END:STANDARD
      BEGIN:DAYLIGHT
      DTSTART:16010101T020000
      TZOFFSETFROM:+0100
      TZOFFSETTO:+0200
      RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=-1SU;BYMONTH=3
      END:DAYLIGHT
      END:VTIMEZONE
    ');

    $this->assertEquals(
      new TimeZone(
        'W. Europe Standard Time',
        TimeZoneInfo::with()
          ->dtstart('16010101T030000')
          ->tzoffsetfrom('+0200')
          ->tzoffsetto('+0100')
          ->rrule('FREQ=YEARLY;INTERVAL=1;BYDAY=-1SU;BYMONTH=10')
          ->create()
        ,
        TimeZoneInfo::with()
          ->dtstart('16010101T020000')
          ->tzoffsetfrom('+0100')
          ->tzoffsetto('+0200')
          ->rrule('FREQ=YEARLY;INTERVAL=1;BYDAY=-1SU;BYMONTH=3')
          ->create()
      ),
      $timezone
    );
  }
}