<?php namespace text\ical;

/**
 * Attendee role
 *
 * @see  xp://text.ical.Attendee#role
 * @see  http://tools.ietf.org/html/rfc2445#section-4.2.16
 */
abstract class Role {
  const CHAIR           = 'CHAIR';
  const REQ_PARTICIPANT = 'REQ-PARTICIPANT';
  const OPT_PARTICIPANT = 'OPT-PARTICIPANT';
  const NON_PARTICIPANT = 'NON-PARTICIPANT';
}