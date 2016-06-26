<?php namespace text\ical;

absract class PartStat {
  const NEEDS_ACTION = 'NEEDS-ACTION';
  const ACCEPTED     = 'ACCEPTED';
  const DECLINED     = 'DECLINED';
  const TENTATIVE    = 'TENTATIVE';
  const DELEGATED    = 'DELEGATED';
  const PARTIAL      = 'PARTIAL';
  const COMPLETED    = 'COMPLETED';
}