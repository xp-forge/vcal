<?php namespace text\ical;

use lang\mirrors\TypeMirror;
use lang\FormatException;

class Creation {
  const ROOT = '';

  private static $definitions= [null, null, [
    'calendar' => [Calendar::class, ['events' => 'event'], [
      'event'      => [Event::class, ['attendees' => 'attendee'], [
        'organizer'   => [Organizer::class],
        'attendee'    => [Attendee::class],
        'summary'     => [Text::class],
        'description' => [Text::class],
        'comment'     => [Text::class],
        'location'    => [Text::class],
        'dtstart'     => [Date::class],
        'dtend'       => [Date::class],
        'alarm'       => [Alarm::class, null, [
          'trigger'     => [Trigger::class]
        ]]
      ]],
      'timezone'   => [TimeZone::class, null, [
        'standard'    => [TimeZoneInfo::class],
        'daylight'    => [TimeZoneInfo::class],
      ]],
    ]]
  ]];


  private $definition, $type, $parent;
  private $members= [], $set= [];

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

    $constructor= (new TypeMirror($definitions[0]))->constructor();
    foreach ($constructor->parameters() as $parameter) {
      $name= $parameter->name();
      if (isset($definitions[1][$name])) {
        $this->set[$definitions[1][$name]]= $name;
      } else {
        $this->set[$name]= false;
      }
      $this->members[$name]= null;
    }
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
    if (!isset($this->set[$member])) {
      $this->members['properties'][$member]= $value;
    } else if ($this->set[$member]) {
      $this->members[$this->set[$member]][]= $value;
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
    $type= new TypeMirror($this->definition[0]);
    return $type->constructor()->newInstance(...array_values($this->members));
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