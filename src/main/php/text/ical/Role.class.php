<?php namespace text\ical;

/**
 * Attendee role
 *
 * @see  xp://text.ical.Attendee#role
 */
abstract class Role {
  const CHAIR           = 'CHAIR';
  const REQ_PARTICIPANT = 'REQ-PARTICIPANT';
  const NON_PARTICIPANT = 'NON-PARTICIPANT';
}