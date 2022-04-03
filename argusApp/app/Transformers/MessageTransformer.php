<?php

namespace App\Transformers;

class MessageTransformer extends Transformer
{
    /**
     * Transform an item
     *
     * @param $user
     * @return mixed
     */
    public function transform($message)
    {

      date_default_timezone_set('America/Toronto');
      //gets timestamp age
      $date_and_time = date('m/d/Y H:i:s', $message->generated_at);

      return [
        'userId' => $message->user_id,
        'id' => $message->id,
        'message' => $message->message,
        'type' => $message->type,
        'generatedAt' => $date_and_time
      ];
        //seems to change format of a record, basically just changes the camel case to snake case here
        //used in controllers
    }
}