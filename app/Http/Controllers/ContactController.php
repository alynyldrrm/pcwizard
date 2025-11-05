<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact'); // resources/views/contact.blade.php
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Message::create($validated);

        // Ana sayfaya geri dön ve başarı mesajı göster
    return redirect()->route('home')->with('success', 'Mesajınız başarıyla gönderildi!');

    }
}
