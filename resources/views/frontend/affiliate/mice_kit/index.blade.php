@extends('layouts.frontend')

@section('seo_title', 'Digital MICE Kit - Bell Hotel Merauke')

@section('content')
    {{-- 1. HERO HEADER --}}
    <div class="relative bg-gray-900 pt-24 pb-16 sm:pb-20 overflow-hidden">
        {{-- Background Decoration --}}
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-yellow-500 rounded-full opacity-10 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 bg-blue-600 rounded-full opacity-10 blur-3xl"></div>
        
        <div class="container mx-auto px-4 relative z-10 text-center">
            <nav class="flex justify-center text-sm text-gray-400 mb-4">
                <a href="{{ route('affiliate.dashboard') }}" class="hover:text-yellow-500 transition-colors">Dashboard</a>
                <span class="mx-2">/</span>
                <span class="text-white">MICE Kit</span>
            </nav>
            <h1 class="text-3xl md:text-5xl font-heading font-bold text-white mb-4 tracking-tight shadow-sm">
                Digital MICE Kit
            </h1>
            <p class="text-gray-400 max-w-2xl mx-auto text-sm md:text-base">
                Unduh materi promosi resmi (Brosur, Video, Foto) untuk membantu Anda menawarkan layanan kami.
            </p>
        </div>
    </div>

    {{-- 2. MAIN CONTENT --}}
    <div class="bg-gray-50 py-12 min-h-[60vh]">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            
            @if($miceKits->isEmpty())
                <div class="flex flex-col items-center justify-center py-16 bg-white rounded-3xl shadow-sm border border-gray-100">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mb-4">
                        <i class="fas fa-folder-open text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Materi Belum Tersedia</h3>
                    <p class="text-gray-500 text-sm">Silakan periksa kembali nanti untuk materi promosi terbaru.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($miceKits as $kit)
                        @php
                            $extension = '';
                            if ($kit->original_filename) {
                                $extension = strtolower(pathinfo($kit->original_filename, PATHINFO_EXTENSION));
                            }
                            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']);
                            $isPdf = $extension === 'pdf';
                            $isVideoFile = in_array($extension, ['mp4', 'mov', 'ogg', 'qt']);
                        @endphp

                        <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden flex flex-col h-full">
                            
                            {{-- PREVIEW AREA --}}
                            <div class="relative h-56 bg-gray-100 overflow-hidden group-hover:bg-gray-200 transition-colors flex items-center justify-center">
                                
                                {{-- Video Preview --}}
                                @if ($kit->type == 'video' || ($kit->type == 'file' && $isVideoFile))
                                    <video class="w-full h-full object-cover" controls preload="metadata">
                                        <source src="{{ route('affiliate.mice-kit.stream', $kit->id) }}" type="{{ Storage::disk('private')->mimeType($kit->path_or_link) }}">
                                        Browser Anda tidak mendukung tag video.
                                    </video>
                                    {{-- Video Overlay Icon (Only if not playing/controls hidden initially) --}}
                                    <div class="absolute inset-0 pointer-events-none flex items-center justify-center bg-black/10">
                                        <div class="w-12 h-12 bg-white/90 rounded-full flex items-center justify-center text-gray-900 shadow-lg">
                                            <i class="fas fa-play ml-1"></i>
                                        </div>
                                    </div>
                                
                                {{-- Image Preview --}}
                                @elseif ($kit->type == 'file' && $isImage)
                                    <img src="{{ route('affiliate.mice-kit.preview', $kit->id) }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700" alt="{{ $kit->title }}">
                                
                                {{-- PDF / Document Preview --}}
                                @else
                                    <div class="text-center p-6">
                                        @if($isPdf)
                                            <i class="fas fa-file-pdf text-5xl text-red-500 mb-2 block drop-shadow-sm"></i>
                                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Dokumen PDF</span>
                                        @else
                                            <i class="fas fa-file-alt text-5xl text-gray-400 mb-2 block"></i>
                                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">File {{ strtoupper($extension) }}</span>
                                        @endif
                                    </div>
                                @endif

                                {{-- Badge Type --}}
                                <div class="absolute top-4 right-4">
                                    @if ($kit->type == 'video' || $isVideoFile)
                                        <span class="bg-blue-500/90 text-white text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wide shadow-sm backdrop-blur-sm">Video</span>
                                    @elseif($isPdf)
                                        <span class="bg-red-500/90 text-white text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wide shadow-sm backdrop-blur-sm">PDF</span>
                                    @else
                                        <span class="bg-gray-800/90 text-white text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wide shadow-sm backdrop-blur-sm">File</span>
                                    @endif
                                </div>
                            </div>

                            {{-- CONTENT BODY --}}
                            <div class="p-6 flex flex-col flex-grow">
                                <h5 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-yellow-600 transition-colors line-clamp-1" title="{{ $kit->title }}">
                                    {{ $kit->title }}
                                </h5>
                                <p class="text-sm text-gray-500 mb-6 line-clamp-3 flex-grow leading-relaxed">
                                    {{ $kit->description }}
                                </p>

                                {{-- Action Buttons --}}
                                <div class="mt-auto pt-4 border-t border-gray-50">
                                    @if ($kit->type == 'video' || $isVideoFile)
                                        <a href="{{ route('affiliate.mice-kit.download', $kit->id) }}" class="flex items-center justify-center w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 rounded-lg text-sm transition-all shadow-md hover:shadow-lg">
                                            <i class="fas fa-download mr-2"></i> Download Video
                                        </a>
                                    @elseif ($isPdf)
                                        <div class="grid grid-cols-2 gap-3">
                                            <a href="{{ route('affiliate.mice-kit.preview', $kit->id) }}" target="_blank" class="flex items-center justify-center bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold py-2.5 rounded-lg text-sm transition-all">
                                                <i class="fas fa-eye mr-2"></i> Lihat
                                            </a>
                                            <a href="{{ route('affiliate.mice-kit.download', $kit->id) }}" class="flex items-center justify-center bg-gray-900 hover:bg-gray-800 text-white font-bold py-2.5 rounded-lg text-sm transition-all">
                                                <i class="fas fa-download mr-2"></i> Unduh
                                            </a>
                                        </div>
                                    @else
                                        <a href="{{ route('affiliate.mice-kit.download', $kit->id) }}" class="flex items-center justify-center w-full bg-gray-900 hover:bg-gray-800 text-white font-bold py-2.5 rounded-lg text-sm transition-all shadow-md hover:shadow-lg">
                                            <i class="fas fa-download mr-2"></i> Download File
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
@endsection