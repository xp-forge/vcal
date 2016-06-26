ICal: Calendar and events
=========================

[![Build Status on TravisCI](https://secure.travis-ci.org/xp-forge/ical.png)](http://travis-ci.org/xp-forge/ical)
[![XP Framework Module](https://raw.githubusercontent.com/xp-framework/web/master/static/xp-framework-badge.png)](https://github.com/xp-framework/core)
[![BSD Licence](https://raw.githubusercontent.com/xp-framework/web/master/static/licence-bsd.png)](https://github.com/xp-framework/core/blob/master/LICENCE.md)
[![Required PHP 5.6+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-5_6plus.png)](http://php.net/)
[![Supports PHP 7.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-7_0plus.png)](http://php.net/)
[![Supports HHVM 3.5+](https://raw.githubusercontent.com/xp-framework/web/master/static/hhvm-3_5plus.png)](http://hhvm.com/)
[![Latest Stable Version](https://poser.pugx.org/xp-forge/ical/version.png)](https://packagist.org/packages/xp-forge/ical)


I/O
---
Calendars can be read and written using the ICalendar class

```php
use text\ical\ICalendar;

$ical= new ICalendar();

$calendar= $ical->read('BEGIN:VCALENDAR...');
$calendar= $ical->read(Console::$in->getStream());
$calendar= $ical->read(new File('meeting.ics'));

$ical->write($calendar, Console::$out->getStream());
$ical->write($calendar, new File('meeting.ics'));
```

Creation
--------
Calendar instances can be created using a fluent interface

```php
use text\ical\Calendar;
use text\ical\Event;
use text\ical\Organizer;
use text\ical\Attendee;
use text\ical\Date;
use text\ical\Text;

$calendar= Calendar::with()
  ->method('REQUEST')
  ->prodid('Microsoft Exchange Server 2010')
  ->version('2.0')
  ->event(Event::with()
    ->organizer(new Organizer('The Organizer', 'MAILTO:organizer@example.com'))
    ->attendee([
      Attendee::with()
        ->role('REQ-PARTICIPANT')
        ->partstat('NEEDS-ACTION')
        ->rsvp('TRUE')
        ->cn('The Attendee 1')
        ->value('MAILTO:attendee2@example.com')
        ->create()
      ,
      Attendee::with()
        ->role('REQ-PARTICIPANT')
        ->partstat('NEEDS-ACTION')
        ->rsvp('TRUE')
        ->cn('The Attendee 2')
        ->value('MAILTO:attendee3@example.com')
        ->create()
    ])
    ->dtstart(new Date('W. Europe Standard Time', '20160524T183000'))
    ->dtend(new Date('W. Europe Standard Time', '20160524T190000'))
    ->location(new Text('de-DE', 'BS 50 EG 0102'))
    ->summary(new Text('de-DE', 'Treffen'))
    ->create()
  )
  ->create()
;
```