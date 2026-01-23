<?php

namespace App\Http\Controllers\cms;
use Twilio\Rest\Client;
use Exception;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Twilio\TwiML\MessagingResponse;

class WhatsAppController extends Controller
{
    public function sendWhatsappMessage($receiverNumber, $messageBody = "This is a message from your Laravel app using Twilio!")
    {

        try {
            $accountSid = env('TWILIO_ACCOUNT_SID');
            $authToken = env('TWILIO_AUTH_TOKEN');
            $twilioWhatsAppNumber = env('TWILIO_WHATSAPP_NUMBER');

            $client = new Client($accountSid, $authToken);
            $client->messages->create(
                "whatsapp:$receiverNumber", // Recipient number must also be prefixed
                [
                    "from" => "whatsapp:$twilioWhatsAppNumber",
                    "body" => $messageBody
                ]
            );

            \Log::info("Message sent to $receiverNumber");
            return response()->json(['success' => true, 'message' => 'WhatsApp message sent successfully.']);
            return back()->with(['success' => "Message sent successfully!"]);

        } catch (Exception $e) {
            \Log::error("Error sending WhatsApp message: " . $e->getMessage() . ' number: ' . $twilioWhatsAppNumber);
            return back()->with(['error' => $e->getMessage()]);
        }
    }


    public function handleInboundMessage(Request $request)
    {
        $incomingMsg = strtolower(trim($request->input('Body')));
        $response = new MessagingResponse();

        // Tailored response logic
        if ($incomingMsg == 'hello') {
            $response->message("Hi there! Welcome to our 2026 automated service.");
        } elseif ($incomingMsg == 'status') {
            $response->message("All systems are currently operational.");
        } else {
            $response->message("Sorry, I didn't understand that. Try 'hello' or 'status'.");
        }

        return response($response)->header('Content-Type', 'text/xml');
    }
}



