<?php namespace text\ical;

/**
 * Attendee participation status
 *
 * @see  xp://text.ical.Attendee#partstat
 * @see  http://tools.ietf.org/html/rfc2445#section-4.2.12
 */
abstract class PartStat {
  const NEEDS_ACTION = 'NEEDS-ACTION';
  const ACCEPTED     = 'ACCEPTED';
  const DECLINED     = 'DECLINED';
  const TENTATIVE    = 'TENTATIVE';
  const DELEGATED    = 'DELEGATED';
  const PARTIAL      = 'PARTIAL';
  const IN_PROCESS   = 'IN-PROCESS';
  const COMPLETED    = 'COMPLETED';
}