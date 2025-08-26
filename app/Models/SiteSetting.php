<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
  use HasFactory;
  protected $table = 'site_setting';
  protected $primaryKey = 'id';

  public static function getSitesetting($type, $key)
  {
    $rtn = null;
    $site_id = config('site_setting.site_id');
    $site_setting = SiteSetting::where(array('site_id' => $site_id, 'type' => $type, 'key' => $key))->first();
    if ($site_setting != null) {
      $rtn = $site_setting->value;
    }
    return $rtn;
  }
}
