<?php

namespace EmilienKopp\Tests\Models;

use EmilienKopp\DatesFormatter\FormatsDates;

class OtherModel
{
  use FormatsDates;
  public $unpublish_at;

  public function __construct($unpublish_at)
  {
    $this->unpublish_at = $unpublish_at;
  }
};
