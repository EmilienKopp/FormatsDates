<?php

/**
 * Trait to format dates in models in a reflective manner
 * @author EmilienKopp https://github.com/EmilienKopp
 */

namespace EmilienKopp\DatesFormatter;

use Carbon\Carbon;
use Illuminate\Support\Str;
use ReflectionProperty;

trait FormatsDates
{
  private static $DATE_FORMAT = 'Y-m-d';
  private static $DATETIME_FORMAT = 'Y-m-d H:i:s';
  private static $TIME_FORMAT = 'H:i:s';
  private static $SUFFIXES = ['At', 'On', 'Date', 'Time'];

  /**
   * Get formatted date string from a property
   *
   * @param string $property Property name (i.e. 'created_at' or 'publish_date')
   * @param string|null $format Date format string (Carbon compatible)
   * @return string|null
   */
  public function getFormattedDate(string $property, ?string $format = null): ?string
  {
    if(!property_exists($this, $property)) {
      return null;
    }
    
    $value = $this->{$property};
    if (empty($value)) {
      return null;
    }

    try {
      $format ??= $this->determineFormat($property);
      return Carbon::parse($value)->format($format);
    } catch (\Exception $e) {
      return null;
    }
  }

  /**
   * Override __call for dynamic method calls for date formatting
   * @param string $method Method name - should be a property name with a suffix (i.e. 'created_at')
   * @param array $arguments Arguments passed to the method (date format string in this case)
   */
  public function __call($method, $arguments)
  {
    if (Str::endsWith($method, self::$SUFFIXES)) {
      $property = Str::snake($method);

      $format = $arguments[0] ?? null;

      return $this->getFormattedDate($property, $format);
    }

    return parent::__call($method, $arguments);
  }

  /**
   * Determine format based on property type hints or name
   * @param string $property Property name (i.e. 'created_at' or 'publish_date')
   */
  private function determineFormat(string $property): string
  {
    $format_mapping = [
      'datetime' => self::$DATETIME_FORMAT,
      'date' => self::$DATE_FORMAT,
      'time' => self::$TIME_FORMAT,
    ];

    try {
      $reflector = new ReflectionProperty(get_class($this), $property);
      $type = $reflector->getType();

      if ($type && $type->getName() === 'DateTime') {
        return self::$DATETIME_FORMAT;
      }

      if (Str::contains($property, 'datetime')) {
        return self::$DATETIME_FORMAT;
      }
      if (Str::contains($property, 'time') && !Str::contains($property, 'date')) {
        return self::$TIME_FORMAT;
      }

      return self::$DATE_FORMAT;
    } catch (\ReflectionException $e) {
      return self::$DATE_FORMAT;
    }
  }

  /** Getters & Setters **/
  /**
   * Set default date format
   * @param string $format Date format - Carbon compatible
   * @return void
   * @warning This will change each model inheriting this trait **individually**
   */
  public static function setDefaultDateFormat(string $format): void
  {
    self::$DATE_FORMAT = $format;
  }

  /**
   * Get default date format
   * @return string
   */
  public static function getDefaultDateFormat(): string
  {
    return self::$DATE_FORMAT;
  }

  /**
   * Set default datetime format
   * @param string $format Date format - Carbon compatible
   * @return void
   * @warning This will change each model inheriting this trait **individually**
   */
  public static function setDefaultDatetimeFormat(string $format): void
  {
    self::$DATETIME_FORMAT = $format;
  }

  /**
   * Get default datetime format
   * @return string
   */
  public static function getDefaultDatetimeFormat(): string
  {
    return self::$DATETIME_FORMAT;
  }

  /**
   * Set default time format
   * @param string $format Date format - Carbon compatible
   * @return void
   * @warning This will change each model inheriting this trait **individually**
   */
  public static function setDefaultTimeFormat(string $format): void
  {
    self::$TIME_FORMAT = $format;
  }
}
