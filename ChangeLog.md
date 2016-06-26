ICal ChangeLog
==============

## ?.?.? / ????-??-??

* Introduced class constants:
  - `text.ical.Method` for calendar methods, e.g. *REQUEST* or *REPLY*
  - `text.ical.Role` for roles, e.g. *CHAIR* or *REQ-PARTICIPANT*.
  - `text.ical.PartStat` for status, e.g. *NEEDS-ACTION* or *TENTATIVE*
  (@thekid)

## 0.4.0 / 2016-06-26

* Wrapped lines after 75 characters. The implementation will keep pairs
  together and use `wordwrap()` for the content, splitting long words
  when necessary - issue #1, part 3
  (@thekid)
* Support line continuation via TAB characters, too - issue #1, part 2
  (@thekid)
* Changed output to always use `\r\n` as mandated by spec. See issue #1
  (@thekid)

## 0.3.0 / 2016-06-26

* **Heads up**: Renamed everything to **ical**: iCalendar is the successor
  of vCalendar, see https://en.wikipedia.org/wiki/ICalendar#vCalendar_1.0
  (@thekid)

## 0.2.0 / 2016-06-24

* Implemented `VALARM` - @thekid

## 0.1.0 / 2016-06-24

* Hello World! First release - @thekid