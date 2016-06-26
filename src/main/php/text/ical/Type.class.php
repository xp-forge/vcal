<?php namespace text\ical;

/**
 * Attendee type
 *
 * @see  xp://text.ical.Attendee#type
 * @see  http://tools.ietf.org/html/rfc2445#section-4.2.3
 */
abstract class Type {
  const INDIVIDUAL = 'INDIVIDUAL';
  const ROOM       = 'ROOM';
  const GROUP      = 'GROUP';
  const RESOURCE   = 'RESOURCE';
}