<?php

namespace App\Http\Controllers;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class MailController extends Controller
{
    public function submitContactForm(Request $request)
    {
        try {
            // Validate the form inputs and reCAPTCHA
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'sender_email' => 'required|email|max:255',
                'message' => 'required|string',
                'g-recaptcha-response' => 'required',
            ]);

            // Verify reCAPTCHA
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => env('RECAPTCHA_SECRET_KEY'),
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip(),
            ]);

            $captchaValidation = json_decode($response->body());

            if (!$captchaValidation->success) {
                return back()->withErrors(['g-recaptcha-response' => 'reCAPTCHA validation failed. Please try again.']);
            }

            // Send the email with the updated HTML structure
            Mail::send([], [], function ($message) use ($validatedData) {
                $message->to('naif.alshaye@gdood.com')  // Update to your recipient's email
                ->subject('New Contact Form Message')
                    ->html('<h1>Message from ' . $validatedData['name'] . '</h1><p>' . $validatedData['message'] . '</p>')
                    ->from($validatedData['sender_email'], $validatedData['name']);
            });

            // Redirect back to the landing page with a success message
            return redirect()->back()->with('success', 'Thank you for contacting us! Your message has been sent.');
        } catch (Exception $e) {
            logger($e->getMessage());
        }
    }

    public function subscribe(Request $request)
    {

        $email_address = $request->input('email_address');

        // Validate the email address
        $validatedData = $request->validate([
            'email_address' => 'required|email'
        ]);

        // Prepare data for Mailchimp API
        $data = [
            'email_address' => $email_address,
            'status' => 'subscribed', // Set the status to "subscribed"
        ];

        $listId = env('MAILCHIMP_LIST_ID'); // Your Mailchimp List ID
        $apiKey = env('MAILCHIMP_API_KEY'); // Your Mailchimp API Key

        try {
            $client = new Client();
            // Mailchimp API request to add member to list
            $response = $client->request('POST', "https://us11.api.mailchimp.com/3.0/lists/{$listId}/members/", [
                'auth' => ['user', $apiKey], // Basic authentication
                'json' => $data,
                'http_errors' => false
            ]);

            // Get the response status and body
            $statusCode = $response->getStatusCode();
            $body = json_decode($response->getBody()->getContents(), true);

            // Check if the status code is 200 and user is subscribed
            if ($statusCode == 200 && isset($body['status']) && $body['status'] == 'subscribed') {
                logger(222);
                return redirect()->back()->with('success', 'Thank you for subscribing!');
            } else {
                // Handle errors
                if (isset($body['title']) && $body['title'] == 'Member Exists') {
                    return redirect()->back()->with('error', 'Email Address already exists!');
                } else {
                    return redirect()->back()->with('error', $body['title'] ?? 'An error occurred. Please try again.');
                }
            }
        } catch (\Exception $e) {
            // Handle any exceptions that may occur
            return redirect()->back()->with('error', 'An unexpected error occurred. Please try again later.');
        }
    }
}
