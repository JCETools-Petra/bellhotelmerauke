@extends('layouts.frontend')

@section('seo_title', 'Terms & Conditions - ' . ($settings['website_title'] ?? 'Bell Hotel Merauke'))
@section('meta_description', 'Syarat dan Ketentuan penggunaan layanan Bell Hotel Merauke. Harap baca dengan seksama sebelum melakukan pemesanan.')

@section('content')
    {{-- 1. HERO HEADER --}}
    <div class="relative bg-gray-900 pt-32 pb-20 sm:pt-40 sm:pb-24 overflow-hidden">
        <div class="absolute inset-0 z-0 opacity-20">
             <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-yellow-500 rounded-full blur-3xl"></div>
             <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 bg-blue-600 rounded-full blur-3xl"></div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10 text-center">
            <span class="text-yellow-500 font-bold uppercase tracking-[0.2em] text-sm mb-4 block animate-fade-in-up">Legal Information</span>
            <h1 class="text-4xl md:text-6xl font-heading font-bold text-white mb-6 tracking-tight animate-fade-in-up delay-100">
                Syarat & <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-yellow-600">Ketentuan</span>
            </h1>
        </div>
    </div>

    {{-- 2. TERMS CONTENT --}}
    <div class="bg-gray-50 py-16 sm:py-24 relative -mt-12 z-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-yellow-50 px-8 py-4 border-b border-yellow-100 flex items-center gap-3">
                    <i class="fas fa-file-contract text-yellow-600 text-xl"></i>
                    <span class="text-yellow-800 font-bold text-sm uppercase tracking-wide">Dokumen Resmi</span>
                </div>

                <div class="p-8 md:p-12">
                    @if(!empty($settings['terms_and_conditions']))
                        {{-- Menggunakan ID khusus untuk scoping CSS --}}
                        <div id="terms-content" class="prose prose-lg prose-gray max-w-none">
                            {!! $settings['terms_and_conditions'] !!}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-400">
                                <i class="fas fa-file-alt text-4xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Konten</h3>
                            <p class="text-gray-500">
                                Syarat dan ketentuan belum diatur oleh administrator.
                            </p>
                        </div>
                    @endif
                </div>

                <div class="bg-gray-50 px-8 py-6 border-t border-gray-100 text-center text-sm text-gray-500">
                    <p>Terakhir diperbarui: {{ date('d F Y') }}</p>
                </div>
            </div>

        </div>
    </div>

    {{-- STYLE KHUSUS UNTUK MEMAKSA FORMATTING --}}
    @push('styles')
    <style>
        /* Reset & Force Styling untuk Konten Terms */
        #terms-content {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #374151; /* Gray-700 */
            line-height: 1.8;
        }

        /* Heading Styles */
        #terms-content h1, 
        #terms-content h2, 
        #terms-content h3, 
        #terms-content h4 {
            color: #111827; /* Gray-900 */
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            margin-top: 2em;
            margin-bottom: 0.8em;
            line-height: 1.3;
        }

        #terms-content h1 { font-size: 2.25rem; border-bottom: 2px solid #e5e7eb; padding-bottom: 0.5em; }
        #terms-content h2 { font-size: 1.875rem; }
        #terms-content h3 { font-size: 1.5rem; }

        /* Paragraphs */
        #terms-content p {
            margin-bottom: 1.5em;
        }

        /* Lists (Bullet & Number) - Masalah utama biasanya disini */
        #terms-content ul {
            list-style-type: disc !important; /* Paksa bullet muncul */
            padding-left: 1.5em !important;
            margin-bottom: 1.5em;
        }
        
        #terms-content ol {
            list-style-type: decimal !important; /* Paksa nomor muncul */
            padding-left: 1.5em !important;
            margin-bottom: 1.5em;
        }

        #terms-content li {
            margin-bottom: 0.5em;
            padding-left: 0.5em;
        }

        /* Links */
        #terms-content a {
            color: #ca8a04; /* Yellow-600 */
            text-decoration: underline;
            font-weight: 500;
        }
        #terms-content a:hover {
            color: #a16207; /* Yellow-700 */
        }

        /* Blockquote */
        #terms-content blockquote {
            border-left: 4px solid #e5e7eb;
            padding-left: 1em;
            font-style: italic;
            color: #6b7280;
            margin: 1.5em 0;
        }

        /* Strong/Bold */
        #terms-content strong, #terms-content b {
            font-weight: 700;
            color: #111827;
        }
    </style>
    @endpush

    <style>
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fade-in-up 0.8s ease-out forwards; opacity: 0; }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
    </style>
@endsection