<?php

namespace sa\classes;

class logManager
{
    public $filePath = __DIR__ . "/../sentlog.json";

    function checkExist()
    {
        if (!file_exists($this->filePath)) {
            $myfile = fopen($this->filePath, "w") or die("Filesystem error.");
            fwrite($myfile, "[]");
            fclose($myfile);
        }
    }

    function getOkSend($email)
    {
        $arrayRaw = file_get_contents($this->filePath);
        $array = json_decode($arrayRaw, true);
        if (!array_key_exists($email, $array)) {
            return true;
        }
        $lastSend = $array[$email];

        if (time() > strtotime($lastSend['time'])) {
            return true;
        }
        return false;
    }

   function logSend($email){
       $arrayRaw = file_get_contents($this->filePath);
       $currArray = json_decode($arrayRaw, true);

       $addyArray = array(
           'time' => date('m/d/Y h:i:s a', strtotime("+5 minutes"))
       );

       if (array_key_exists($email, $currArray)) {
           $currArray[$email] = $addyArray;
       }else {
           $currArray[$email]=$addyArray;
       }

       $newData = json_encode($currArray);
       $myfile = fopen($this->filePath, "w") or die("Unable to open file!");
       fwrite($myfile, $newData);
       fclose($myfile);
   }

}