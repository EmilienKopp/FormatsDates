<?php

namespace EmilienKopp\Tests\Models;

use EmilienKopp\DatesFormatter\FormatsDates;

class TestModel
{
    use FormatsDates;
    
    public $created_at;
    public $updated_on;
    public $publish_date;
    public $event_time;

    public function __construct($created_at, $updated_on, $publish_date, $event_time)
    {
        $this->created_at = $created_at;
        $this->updated_on = $updated_on;
        $this->publish_date = $publish_date;
        $this->event_time = $event_time;
    }
}
