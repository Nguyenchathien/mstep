<?php
/*
 * Copyright 2015 SPCVN Co., Ltd.
 * All right reserved.
*/

/**
 * @Author: Nguyen Chat Hien
 * @Date:   2016-08-26 16:05:55
 * @Last Modified by:   Nguyen Chat Hien
 * @Last Modified time: 2016-08-26 17:15:03
 */

class AddShell extends AppShell {

    var $uses = [
                "TblMstepWeatherWeeklyReports",
                "TblMstepWeatherInformations",
                "TblMstepWeatherImages",
        ];

    public function main() {
        set_time_limit(0);

        $dataWeather = $this->TblMstepWeatherWeeklyReports->getWeatherLink();
        foreach ($dataWeather as $key => $value) {
            $results = file_get_contents($value);
            $data = simplexml_load_string($results);
            $dataJson = json_encode($data);
            $dataNew = json_decode($dataJson);
            $currentDate = date('Y-m-d', strtotime($dataNew->channel->lastBuildDate));
            $dataUse = array_splice($dataNew->channel->item, 0, 8);

            foreach ($dataUse as $item) {
                $dataWeather = $this->TblMstepWeatherInformations->findAll();
                foreach ($dataWeather as $v) {
                    if ($v["TblMstepWeatherInformations"]["report_id"] == $key &&  $v["TblMstepWeatherInformations"]["date"] == $currentDate) {
                        $this->TblMstepWeatherInformations->delete($v["TblMstepWeatherInformations"]["id"]);
                    }
                }
                $this->TblMstepWeatherInformations->create();
                $this->TblMstepWeatherInformations->save(
                    array(
                        'report_id'     => $key,
                        'img_id'        => $this->getWeatherImageId($item->description),
                        'date'          => $currentDate,
                        'description'   => $item->description
                    ));

                $currentDate = date('Y-m-d', strtotime('+1 days', strtotime($currentDate)));
            }
        }
    }

    function getWeatherImageId($name) {

        $arrImages = $this->TblMstepWeatherImages->getWeatherImages();
        $image_id = 1;
        foreach ($arrImages as $k => $v) {
            if (strlen(strstr($name, $v)) > 0) {
                $image_id = $k;
            }
        }
        return $image_id;
    }

}
