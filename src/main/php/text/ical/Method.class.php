<?php namespace text\ical;

/**
 * Calendar methods
 *
 * @see  xp://text.ical.Calendar#method
 * @see  http://tools.ietf.org/html/rfc2446#section-1.3
 */
abstract class Method {
  const PUBLISH        = 'PUBLISH';
  const REQUEST        = 'REQUEST';
  const REPLY          = 'REPLY';
  const ADD            = 'ADD';
  const CANCEL         = 'CANCEL';
  const REFRESH        = 'REFRESH';
  const COUNTER        = 'COUNTER';
  const DECLINECOUNTER = 'DECLINECOUNTER';
}