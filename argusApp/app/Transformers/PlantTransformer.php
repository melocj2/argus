<?php

namespace App\Transformers;

class PlantTransformer extends Transformer
{
    /**
     * Transform an item
     *
     * @param $user
     * @return mixed
     */
    public function transform($plant)
    {
      //gets timestamp age
      $age = time() - $plant->date_planted;

      //gets age in years with decimals
      $yearsFull = $age/(86400*365);

       //gets years rounded down
       $years = floor($yearsFull);

       //gets fraction of years leftover after full years are accounted for
       $remainder = $yearsFull - $years;

      //gets age in months rounded down
      $days = floor(365*$remainder);

      //converts years to displayable string
      $years = $years == 1 ? $years . " year and " : $years . " years and ";

      //converts months to displayable string
      $days = $days == 1 ? $days . " day." : $days . " days.";

      //concatenates years and months
      $age = $years . $days;

      return [
        'userId' => $plant->user_id,
        'id' => $plant->id,
        'name' => $plant->name,
        'image' => $plant->image,
        'type' => $plant->type,
        'age' => $age,
        'location' => $plant->location,
      ];
        //seems to change format of a record, basically just changes the camel case to snake case here
        //used in controllers
    }
}