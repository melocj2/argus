<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Message;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Transformers\MessageTransformer;
use Twilio\Rest\Client;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, MessageTransformer $messageTransformer)
    {

        $message = Message::find($id);

        $time = $request->message ? time() : null;

        $message->message = $request->message;
        $message->generated_at = $time;

        $message->save();

        $message->fresh();

        return response()->json($messageTransformer->transform($message));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Gets all plants belonging to the given user
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @param MessageTransformer $messageTransformer
     */
    public function getMessageList($id, MessageTransformer $messageTransformer) {

        $messages = Message::where('user_id', $id)->get();

        if (count($messages) < 1) {

            $message = new Message();
            $message->user_id = $id;
            $message->type = 'gas';
            $message->generated_at = time();
            $message->save();

            $message = new Message();
            $message->user_id = $id;
            $message->type = 'light';
            $message->generated_at = time();
            $message->save();

            $message = new Message();
            $message->user_id = $id;
            $message->type = 'moisture';
            $message->generated_at = time();
            $message->save();

            $message = new Message();
            $message->user_id = $id;
            $message->type = 'temp';
            $message->generated_at = time();
            $message->save();

            $messages = Message::where('user_id', $id)->get();
        }

        $messagesTransformed = [];

        foreach ($messages as $item) {
            $messagesTransformed[] = $messageTransformer->transform($item);
        }

        return response()->json($messagesTransformed);
    }

    /**
     * Gets all plants belonging to the given user
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @param MessageTransformer $messageTransformer
     */
    public function sendAlert(Request $request) {

        // // Update the path below to your autoload.php,
        // // see https://getcomposer.org/doc/01-basic-usage.m

        // Find your Account SID and Auth Token at twilio.com/console
        // and set the environment variables. See http://twil.io/secure
        $sid = getenv("TWILIO_SID");
        $token = getenv("TWILIO_TOKEN");
        $twilio = new Client($sid, $token);

        // +12269777826 natasha
        // +15192007926 jake

        $message = $twilio->messages
                        ->create("+15192007926", // to
                                [
                                    "body" => $request->message,
                                    "from" => "+12565889944"
                                ]
                        );

        print($message->sid);

        return response()->json($message);
    }

}
