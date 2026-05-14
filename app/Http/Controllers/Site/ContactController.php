<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        $faqs = Faq::active()->forPage('iletisim')->orderBy('sort_order')->limit(6)->get();

        return view('site.contact', [
            'faqs' => $faqs,
            'schemaType' => 'LocalBusiness',
        ]);
    }
}
