<?php namespace text\vcal\unittest;

use text\vcal\Calendar;
use text\vcal\Event;
use text\vcal\Organizer;
use text\vcal\Attendee;
use text\vcal\Text;
use text\vcal\Date;
use text\vcal\Alarm;
use text\vcal\Trigger;
use text\vcal\TimeZone;
use text\vcal\TimeZoneInfo;

class Fixtures extends \lang\Enum {
  public static $calendar, $event, $timezone, $alarm;

  static function __static() {
    $i= 0;

    self::$calendar= new self(
      $i++,
      'calendar',
      '
        BEGIN:VCALENDAR
        METHOD:REQUEST
        PRODID:Microsoft Exchange Server 2010
        VERSION:2.0
        END:VCALENDAR
      ',
      Calendar::with()
        ->method('REQUEST')
        ->prodid('Microsoft Exchange Server 2010')
        ->version('2.0')
        ->event(null)
        ->create()
    );

    self::$event= new self(
      $i++,
      'event',
      '
        BEGIN:VEVENT
        ORGANIZER;CN=The Organizer:MAILTO:organizer@example.com
        ATTENDEE;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE;CN="=Attendee:Nr;1":MAILTO:attendee1@example.com
        ATTENDEE;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE;CN=The Attendee 2:MAILTO:attendee2@example.com
        ATTENDEE;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE;CN=The Attendee 3:MAILTO:attendee3@example.com
        COMMENT;LANGUAGE=de-DE:\n
        SUMMARY;LANGUAGE=de-DE:Treffen
        DTSTART;TZID=W. Europe Standard Time:20160524T183000
        DTEND;TZID=W. Europe Standard Time:20160524T190000
        LOCATION;LANGUAGE=de-DE:BS 50 EG 0102
        END:VEVENT
      ',
      Event::with()
        ->organizer(Organizer::with()->cn('The Organizer')->value('MAILTO:organizer@example.com')->create())
        ->attendee([
          Attendee::with()
            ->role('REQ-PARTICIPANT')
            ->partstat('NEEDS-ACTION')
            ->rsvp('TRUE')
            ->cn('=Attendee:Nr;1')
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
    );

    self::$timezone= new self(
      $i++,
      'timezone',
      '
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
      ',
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
      )
    );

    self::$alarm= new self(
      $i++,
      'alarm',
      '
        BEGIN:VALARM
        DESCRIPTION:REMINDER
        TRIGGER;RELATED=START:-PT15M
        ACTION:DISPLAY
        END:VALARM
      ',
      Alarm::with()
        ->description('REMINDER')
        ->trigger(new Trigger('START', '-PT15M'))
        ->action('DISPLAY')
        ->create()
    );

  }

  /**
   * Creates a new fixture
   *
   * @param  int $ordinal
   * @param  string $name
   * @param  string $string
   * @param  var $object
   */
  public function __construct($ordinal, $name, $string, $object) {
    parent::__construct($ordinal, $name);
    $this->string= $string;
    $this->object= $object;
  }

  /** @return string */
  public function string() { return trim(preg_replace("/\n\s+/", "\n", $this->string)); }

  /** @return var */
  public function object() { return $this->object; }
}