<?php namespace text\ical;

/**
 * Attendee type
 *
 * @see  xp://text.ical.Attendee#type
 */
abstract class Type {
  const INDIVIDUAL = 'INDIVIDUAL';
  const ROOM       = 'ROOM';
}