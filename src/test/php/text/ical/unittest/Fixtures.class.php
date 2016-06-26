<?php namespace text\ical\unittest;

use text\ical\Calendar;
use text\ical\Event;
use text\ical\Organizer;
use text\ical\Attendee;
use text\ical\Text;
use text\ical\Date;
use text\ical\Alarm;
use text\ical\Trigger;
use text\ical\TimeZone;
use text\ical\TimeZoneInfo;

class Fixtures extends \lang\Enum {
  public static $calendar, $event, $timezone, $alarm, $quoting;

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
        ->events(null)
        ->create()
    );

    self::$event= new self(
      $i++,
      'event',
      '
        BEGIN:VCALENDAR
        BEGIN:VEVENT
        ORGANIZER;CN=The Organizer:MAILTO:organizer@example.com
        ATTENDEE;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE;
         CN=The Attendee 1:MAILTO:attendee1@example.com
        ATTENDEE;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE;
         CN=The Attendee 2:MAILTO:attendee2@example.com
        ATTENDEE;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE;
         CN=The Attendee 3:MAILTO:attendee3@example.com
        COMMENT;LANGUAGE=de-DE:\n
        SUMMARY;LANGUAGE=de-DE:Treffen
        DTSTART;TZID=W. Europe Standard Time:20160524T183000
        DTEND;TZID=W. Europe Standard Time:20160524T190000
        LOCATION;LANGUAGE=de-DE:BS 50 EG 0102
        END:VEVENT
        END:VCALENDAR
      ',
      Calendar::with()->events([Event::with()
        ->organizer(Organizer::with()->cn('The Organizer')->value('MAILTO:organizer@example.com')->create())
        ->attendees([
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
      ])
      ->create()
    );

    self::$timezone= new self(
      $i++,
      'timezone',
      '
        BEGIN:VCALENDAR
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
        END:VCALENDAR
      ',
      Calendar::with()->timezone(new TimeZone(
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
      ))
      ->create()
    );

    self::$alarm= new self(
      $i++,
      'alarm',
      '
        BEGIN:VCALENDAR
        BEGIN:VEVENT
        BEGIN:VALARM
        DESCRIPTION:REMINDER
        TRIGGER;RELATED=START:-PT15M
        ACTION:DISPLAY
        END:VALARM
        END:VEVENT
        END:VCALENDAR
      ',
      Calendar::with()->events([
        Event::with()->alarm(Alarm::with()
          ->description('REMINDER')
          ->trigger(new Trigger('START', '-PT15M'))
          ->action('DISPLAY')
          ->create()
        )
        ->create()
      ])->create()
    );

    self::$quoting= new self(
      $i++,
      'quoting',
      '
        BEGIN:VCALENDAR
        BEGIN:VEVENT
        ATTENDEE;CN="Semi;Colon":MAILTO:participant1@example.com
        ATTENDEE;CN="Col:On":MAILTO:participant2@example.com
        ATTENDEE;CN="Equal=s":MAILTO:participant3@example.com
        END:VEVENT
        END:VCALENDAR
      ',
      Calendar::with()->events([Event::with()
        ->attendees([
          Attendee::with()
            ->cn('Semi;Colon')
            ->value('MAILTO:participant1@example.com')
            ->create()
          ,
          Attendee::with()
            ->cn('Col:On')
            ->value('MAILTO:participant2@example.com')
            ->create()
          ,
          Attendee::with()
            ->cn('Equal=s')
            ->value('MAILTO:participant3@example.com')
            ->create()
       ])
       ->create()
      ])
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
  public function string() { return trim(preg_replace("/\n\s{8}/", "\r\n", $this->string)); }

  /** @return var */
  public function object() { return $this->object; }
}