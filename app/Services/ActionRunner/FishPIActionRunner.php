<?php

namespace App\Services\ActionRunner;

use App\Command;
use App\Services\ActionRunner\ActionRunner;
use App\FishData;
use Log;

class FishPIActionRunner extends ActionRunner
{

  private $statsTypeList = [
    "Average",
    "Maximum",
    "Minimum"
  ];

  /**
   * Get temperature of air
   * @param  String $text
   * @return String
   */
  protected function getTemperatureAir($text)
  {
    return $this->getTemperature($text, "air");
  }

  /**
   * Get temperature of water
   * @param  String $text
   * @return String
   */
  protected function getTemperatureWater($text)
  {
    return $this->getTemperature($text, "water");
  }

  /**
   * Get current water temp
   */
  protected function getTemperature($text, $type = false)
  {
    Log::notice('Type: '.$type);
    $temp = $this->getTemperatureFromDb($type);
    $this->responseTexts[] = $this->replaceTempInText($text, $temp);
    return true;
  }

  /**
   * Get temperature statistics
   * @param  String   $text
   * @param  Time     $date
   * @param  String   $statsType
   * @param  String   $type
   */
  protected function getTemperatureStats($text, $date, $statsType, $type)
  {
    $temp = $this->getTemperatureStatsFromDb($date, $statsType, $type);
    $this->responseTexts[] = $this->replaceTempInText($text, $temp);
    return true;
  }

  protected function setLedOn($text, $led)
  {
    $this->setLed($text, $led, true, 15);
  }

  /**
   * Set led
   * @param [type] $text   [description]
   * @param [type] $onOrOf [description]
   * @param [type] $led    [description]
   */
  protected function setLed($text, $led, $onOrOff, $time = 10000)
  {
    Log::info("Turn led: ".$led." - ".$onOrOff);
    if (!$led || !$onOrOff) {
      $this->responseTexts[] = $text;
      return false;
    }

    try {
      $command = new Command;
      $command->command = 'setled';
      $command->data = json_encode([
        "led" => $led,
        "onOrOff" => $onOrOff,
        "time" => $time
      ]);
      $command->executed = true;
      $command->save();
    } catch (\Exception $e) {
      Log::info("Db not available");
      return false;
    }

    $this->responseTexts[] = $text;
    return true;
  }

  protected function runDisco()
  {
    try {
      $command = new Command;
      $command->command = 'testrunlights';
      $command->data = json_encode([]);
      $command->executed = true;
      $command->save();

    } catch (\Exception $e) {
      Log::info("Db not available");
      return false;
    }
  }

  protected function getImageOfFishes($text)
  {
    // determine the name of the file to save (max 1 per minute)
    $file = "fishes-".date("Y-m-d-H-i").".png";
    // store the image in the assets resource directory
    $image = resource_path('assets/images/'.$file);

    $this->responseTexts[] = $text;
    $this->responseImages = [];

    // if the file already exists we want to return that one and not regenerate it, so we have a
    // max of 1 image per minute
    if (file_exists($image)) {
      $this->responseImages[] = $file;
      return true;
    } else {
      // get the youtube url of the live stream
      $output = false;
      try {
        \exec("youtube-dl -g https://www.youtube.com/channel/UCjEPY1NzMI2qq6gQFhMiP0Q/live", $output);
      } catch (\Exception $e) {
        Log::error($e->getMessage());
        return false;
      }

      // if we cant get the url return
      if (!$output || !is_array($output)) {
        return false;
      }

      // get the thumbnail from the youtube stream
      $youtubeUrl = $output[0];
      try {
        \exec('ffmpeg -i '.$youtubeUrl.' -vf "thumbnail,scale=1280:720" -frames:v 1 '.$image, $output);
      } catch (\Exception $e) {
        Log::error($e->getMessage());
        return false;
      }

      // return the just created thumbnail
      $this->responseImages[] = $file;
      return true;
    }
    return false;
  }

  protected function iLoveLego()
  {
    $this->responseTexts = [];
    $this->responseImages[] = 'lego.png';
    return true;
  }

  /**
   * Replace temperature within the text
   * @param  String   $text
   * @param  String   $temp
   */
  private function replaceTempInText($text, $temp)
  {
    return str_replace("*temperature*", $temp, $text);
  }

  /**
   * Get temperature stats from database
   * @param  [type] $date      [description]
   * @param  [type] $statsType [description]
   * @param  [type] $type      [description]
   * @return [type]            [description]
   */
  private function getTemperatureStatsFromDb($date, $statsType, $type)
  {
    if (!in_array($statsType, $this->statsTypeList)) {
      Log::notice("Wrong stats type: ".$statsType);
      return "n/a";
    }
    try {
      $data = FishData::whereDate("time", '=', date('Y-m-d',strtotime($date)));
      switch($statsType) {
        case "Average":
          $temp = $data->avg($type);
        break;
        case "Maximum":
          $temp = $data->max($type);
        break;
        case "Minimum":
          $temp = $data->min($type);
        break;
      }
    } catch (\Exception $e) {
      Log::error($e->getMessage());
      return "n/a";
    }
    return $this->toReadableTemp($temp);
  }

  /**
   * get temperature from the database
   * @param  String $type
   * @return String
   */
  private function getTemperatureFromDb($type) {
    if (!$type) {
      return "n/a";
    }
    try {
      $data = FishData::orderBy('time', 'desc')->firstOrFail();
    } catch (\Exception $e) {
      return "n/a";
    }
    return $this->toReadableTemp($data[$type]);
  }

  /**
   * Transform temp to readable temp
   * @param  [type] $temp [description]
   * @return [type]       [description]
   */
  private function toReadableTemp($temp)
  {
    if (!is_numeric($temp)) {
      return $temp;
    }
    $temp = $temp / 1000;
    return round($temp, 2)."º";
  }
}
