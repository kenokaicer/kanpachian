<?php
namespace Dao\Json;

class Json
{
    /**
     * Recieves an array to json serlialize and a filename for where to store it
     */
    public static function Serilize($list, $fileName)
    {
        //--Old way that works with toJson() methos when JsonSerializable wasn't wokring--//
        /*$string = "["; //start with [ and end with ] if an array is to be converted
        $end = count($list);

        if ($end == 1) { //dirty way of serializing, as Serilaizable interface is not working
            $string = $list[0]->toJson();
        } else {
            foreach ($list as $key => $value) {
                if ($key != $end - 1) {
                    $string .= $value->toJson() . ","; 
                } else {
                    $string .= $value->toJson(); //rtrim($string,",") to strip the last,
                }
            }
            $string .= "]";
        }*/
        //--------------------------------------------------------------------------------//
        $json = json_encode($list);

        $fp = fopen($fileName, 'w');
        fwrite($fp, $json);
        fclose($fp);
    }

    /**
     * Opens filename and returns the json string, deserilizing and casting to be done by each list
     */
    public static function Deserilize($fileName)
    {
        if(!file_exists($fileName)){ //check if file exists
            $fp = fopen($fileName, 'x'); //create file
            fclose($fp);
            return "";
        }
        $fp = fopen($fileName, 'r');
        if(filesize($fileName)!=0){
            $string = fread($fp, filesize($fileName));
            fclose($fp);
        }else{
            $string = "";
        }
        
        return $string;
    }
}
