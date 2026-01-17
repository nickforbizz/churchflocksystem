<?php

namespace App\Http\Controllers\cms;
use Twilio\Rest\Client;
use Exception;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WhatsAppController extends Controller
{
    public function sendWhatsappMessage($receiverNumber = "+254707722247", $messageBody = "This is a message from your Laravel app using Twilio!")
    {
        $receiverNumber = $receiverNumber; // Recipient's number in E.164 format

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
}
