<?php namespace text\ical;

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