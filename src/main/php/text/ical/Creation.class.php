<?php namespace text\ical;

use lang\mirrors\TypeMirror;
use lang\FormatException;

class Creation {
  const ROOT = '';

  private static $definitions= [null, null, [
    'calendar'   => [Calendar::class, null, [
      'event'      => [Event::class, 'events', [
        'organizer'   => [Organizer::class, null],
        'attendee'    => [Attendee::class, 'attendees'],
        'summary'     => [Text::class, null],
        'description' => [Text::class, null],
        'comment'     => [Text::class, null],
        'location'    => [Text::class, null],
        'dtstart'     => [Date::class, null],
        'dtend'       => [Date::class, null],
        'alarm'       => [Alarm::class, null, [
          'trigger'     => [Trigger::class, null]
        ]]
      ]],
      'timezone'   => [TimeZone::class, null, [
        'standard'    => [TimeZoneInfo::class, null],
        'daylight'    => [TimeZoneInfo::class, null],
      ]],
    ]
  ]]];

  private $definition, $type, $parent;
  private $members= [];

  /**
   * Create new instance creation 
   *
   * @param  var $definitions Selection of static definitions
   * @param  string $type
   * @param  self $parent
   */
  public function __construct($definitions, $type, $parent) {
    $this->definition= $definitions;
    $this->type= $type;
    $this->parent= $parent;
  }

  /**
   * Nested creation
   *
   * @param  string $type
   * @return self
   * @throws lang.FormatException
   */
  public function of($type) {
    $lookup= strtolower($type);
    if (isset($this->definition[2][$lookup])) {
      return new self($this->definition[2][$lookup], $lookup, $this);
    }

    throw new FormatException(sprintf(
      'Unknown object type "%s" %s',
      $lookup,
      $this->parent ? 'inside "'.$this->type.'"' : 'at root level'
    ));
  }

  /**
   * Root creation
   *
   * @return self
   */
  public static function root() {
    return new self(self::$definitions, self::ROOT, null);
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
    if (isset($this->definition[2][$member][1])) {
      $this->members[$this->definition[2][$member][1]][]= $value;
    } else {
      $this->members[$member]= $value;
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
    $constructor= (new TypeMirror($this->definition[0]))->constructor();
    foreach ($constructor->parameters() as $parameter) {
      $name= $parameter->name();
      $args[]= isset($this->members[$name]) ? $this->members[$name] : null;
    }
    return $constructor->newInstance(...$args);
  }

  /**
   * Close creation
   *
   * @return self
   */
  public function close($type) {
    $lookup= strtolower($type);
    if ($lookup === $this->type) return $this->parent;

    throw new FormatException(sprintf(
      'Illegal nesting of "%s" %s',
      $lookup,
      $this->parent ? 'inside "'.$this->type.'"' : 'at root level'
    ));
  }
}