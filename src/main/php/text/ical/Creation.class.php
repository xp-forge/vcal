<?php namespace text\ical;

use lang\mirrors\TypeMirror;
use lang\IllegalArgumentException;

class Creation {
  private static $types= [
    'vcalendar'   => Calendar::class,
    'vevent'      => Event::class,
    'vtimezone'   => TimeZone::class,
    'valarm'      => Alarm::class,
    'organizer'   => Organizer::class,
    'attendee'    => Attendee::class,
    'standard'    => TimeZoneInfo::class,
    'daylight'    => TimeZoneInfo::class,
    'summary'     => Text::class,
    'trigger'     => Trigger::class,
    'description' => Text::class,
    'comment'     => Text::class,
    'location'    => Text::class,
    'dtstart'     => Date::class,
    'dtend'       => Date::class
  ];

  private $constructor;
  private $members= [];

  /**
   * Create new instance creation 
   *
   * @param  lang.mirror.Constructor $constructor
   */
  public function __construct($constructor) {
    $this->constructor= $constructor;
  }

  /**
   * Set a member 
   *
   * @param  string $type
   * @return self
   */
  public static function of($type) {
    $lookup= strtolower($type);
    if (isset(self::$types[$lookup])) {
      return new self((new TypeMirror(self::$types[$lookup]))->constructor());
    }
    throw new IllegalArgumentException('Unknown object type "'.$type.'"');
  }

  /**
   * Set a member 
   *
   * @param  string $member
   * @param  var $value
   * @return self
   */
  public function with($member, $value) {
    $member= strtolower($member);
    if (!array_key_exists($member, $this->members)) {
      $this->members[$member]= $value;
    } else if (is_array($this->members[$member])) {
      $this->members[$member][]= $value;
    } else {
      $this->members[$member]= [$this->members[$member], $value];
    }
    return $this;
  }

  /**
   * Creates a new instance
   *
   * @return var
   */
  public function create() {
    $args= [];
    foreach ($this->constructor->parameters() as $parameter) {
      $name= $parameter->name();
      $args[]= isset($this->members[$name]) ? $this->members[$name] : null;
    }
    return $this->constructor->newInstance(...$args);
  }
}