<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SynbarraController extends Controller
{
    /**
     * Homepage: Primary conversion page.
     */
    public function home(): Response
    {
        return Inertia::render('Home', [
            'meta' => [
                'title' => 'Synbarra Technologies | Custom Software Development',
                'description' => 'We design and develop custom software solutions that help businesses operate smarter, scale faster, and create meaningful digital experiences.',
            ],
        ]);
    }

    /**
     * About Us: Company identity and credibility.
     */
    public function about(): Response
    {
        return Inertia::render('About', [
            'meta' => [
                'title' => 'About Us | Synbarra Technologies',
                'description' => 'Learn more about Synbarra Technologies, our vision, mission, and commitment to engineering reliable digital solutions.',
            ],
        ]);
    }

    /**
     * Services: Dedicated service overview and business outcomes.
     */
    public function services(): Response
    {
        return Inertia::render('Services', [
            'meta' => [
                'title' => 'Services | Synbarra Technologies',
                'description' => 'Explore our full spectrum of technology services, from custom software and web applications to process automation and system integration.',
            ],
        ]);
    }

    /**
     * Our Approach: Structured engineering methodology.
     */
    public function approach(): Response
    {
        return Inertia::render('Approach', [
            'meta' => [
                'title' => 'Our Approach | Synbarra Technologies',
                'description' => 'Discover our structured five-stage software development process: Discover, Define, Design, Develop, and Deliver.',
            ],
        ]);
    }

    /**
     * Contact: Inquiries and project discussion.
     */
    public function contact(): Response
    {
        return Inertia::render('Contact', [
            'meta' => [
                'title' => 'Contact Us | Synbarra Technologies',
                'description' => 'Let us build something that moves your business forward. Start a conversation with our technology team.',
            ],
        ]);
    }

    /**
     * Handle contact form submission.
     */
    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'fullName' => 'required|string|max:120',
            'companyName' => 'nullable|string|max:150',
            'email' => 'required|email|max:150',
            'whatsappNumber' => 'required|string|max:30',
            'serviceRequired' => 'required|string|max:100',
            'projectDescription' => 'required|string|min:10|max:2000',
        ]);

        // Simpan log atau teruskan ke notifikasi
        \Log::info('New Synbarra Inquiry:', $validated);

        return back()->with('success', 'Thank you! Your project inquiry has been received. Our team will review your requirements and get in touch promptly.');
    }
}
