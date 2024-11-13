<?php

namespace EmilienKopp\Tests;

use EmilienKopp\DatesFormatter\FormatsDates;
use PHPUnit\Framework\TestCase;
use Carbon\Carbon;
use EmilienKopp\Tests\Models\TestModel;
use EmilienKopp\Tests\Models\OtherModel;

class FormatsDatesTest extends TestCase
{
  protected $testModel;
  protected $otherModel;

  protected function setUp(): void
  {
    $this->testModel = new TestModel(
      Carbon::now()->subDays(1)->toDateTimeString(),
      Carbon::now()->subDays(2)->toDateString(),
      Carbon::now()->subDays(3)->toDateString(),
      Carbon::now()->subHours(4)->toTimeString()
    );
    $this->otherModel = new OtherModel(
      Carbon::now()->subDays(1)->toDateTimeString()
    );
  }

  /** @test */
  public function getFormattedDate_formats_at_suffixed_property_with_default_datetime_format()
  {
    $expected = Carbon::parse($this->testModel->created_at)->format(TestModel::getDefaultDateFormat());
    $this->assertEquals($expected, $this->testModel->getFormattedDate('created_at'));
  }

  /** @test */
  public function it_formats_date_suffixed_property_with_default_date_format()
  {
    $expected = Carbon::parse($this->testModel->publish_date)->format('Y-m-d');
    $this->assertEquals($expected, $this->testModel->getFormattedDate('publish_date'));
  }

  /** @test */
  public function it_formats_time_suffixed_property_with_default_time_format()
  {
    $expected = Carbon::parse($this->testModel->event_time)->format('H:i:s');
    $this->assertEquals($expected, $this->testModel->getFormattedDate('event_time'));
  }

  /** @test */
  public function getFormattedDate_uses_custom_format_if_provided()
  {
    $customFormat = 'd/m/Y';
    $expected = Carbon::parse($this->testModel->publish_date)->format($customFormat);
    $this->assertEquals($expected, $this->testModel->getFormattedDate('publish_date', $customFormat));
  }

  /** @test */
  public function getFormattedDate_returns_null_for_empty_date_property()
  {
    $this->testModel->created_at = null;
    $this->assertNull($this->testModel->getFormattedDate('created_at'));
  }

  /** @test */
  public function dynamic_call_works_for_date_suffix()
  {
    $expected = Carbon::parse($this->testModel->publish_date)->format('Y-m-d');
    $this->assertEquals($expected, $this->testModel->publishDate());
  }

  /** @test */
  public function dynamic_call_works_for_time_suffix()
  {
    $expected = Carbon::parse($this->testModel->event_time)->format('H:i:s');
    $this->assertEquals($expected, $this->testModel->eventTime());
  }

  /** @test */
  public function dynamic_call_works_for_at_suffix()
  {
    $expected = Carbon::parse($this->testModel->created_at)->format(TestModel::getDefaultDateFormat());
    $this->assertEquals($expected, $this->testModel->createdAt());
  }

  /** @test */
  public function dynamic_call_to_empty_property_returns_null()
  {
    $this->testModel->created_at = null;
    $this->assertNull($this->testModel->createdAt());
  }

  /** @test */
  public function getFormattedDate_handles_invalid_date_gracefully()
  {
    $this->testModel->created_at = 'invalid-date';
    $this->assertNull($this->testModel->getFormattedDate('created_at'));
  }

  /** @test */
  public function dynamic_call_handles_invalid_date_gracefully()
  {
    $this->testModel->created_at = 'invalid-date';
    $this->assertNull($this->testModel->createdAt());
  }

  /** @test */
  public function getFormattedDate_can_take_an_optional_string_format()
  {
    $inlinedFormat = 'd/m/Y';
    $expected = Carbon::parse($this->testModel->created_at)->format($inlinedFormat);
    $this->assertEquals($expected, $this->testModel->getFormattedDate('created_at', $inlinedFormat));
  }

  /** @test */
  public function dynamic_call_can_take_an_optional_string_format()
  {
    $inlinedFormat = 'd/m/Y';
    $expected = Carbon::parse($this->testModel->created_at)->format($inlinedFormat);
    $this->assertEquals($expected, $this->testModel->createdAt($inlinedFormat));
  }

  /** @test */
  public function it_allows_default_format_to_be_changed_without_affecting_other_models()
  {
    $modifiedFormat = 'd/m/Y';
    TestModel::setDefaultDateFormat($modifiedFormat);
    $expected = Carbon::parse($this->testModel->publish_date)->format($modifiedFormat);
    $this->assertEquals($expected, $this->testModel->getFormattedDate('publish_date'));

    $expectedOther = Carbon::parse($this->otherModel->unpublish_at)->format(FormatsDates::getDefaultDateFormat());
    $this->assertEquals($expectedOther, $this->otherModel->getFormattedDate('unpublish_at'));
  }

  /** @test */
  public function it_returns_null_on_non_existing_property()
  {
    $this->assertEquals(
      null,
      $this->testModel->getFormattedDate('non_existing_property')
    );
  }
}
