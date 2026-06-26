@extends('layouts.app')

@section('title', 'Doanh Nghiệp Bất Động Sản - Đầu Tư & Phát Triển | BDS NKS')

@section('content')
<div class="space-y-16 pb-20 bg-white">
    
    <!-- Hero / Banner Section: Elegant Premium Luxury Design -->
    <section class="relative h-[320px] flex items-center justify-start overflow-hidden">
        <!-- Background Image with Dark Glassmorphism Overlay -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&q=80&w=1920" 
                 alt="Real Estate Corporate Buildings" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-900/60 to-transparent"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="max-w-2xl text-left space-y-4 animate-fade-in-up">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black tracking-widest bg-primary/20 text-primary uppercase border border-primary/20">
                    Bản đồ doanh nghiệp BĐS
                </span>
                <h1 class="text-white font-extrabold text-4xl sm:text-5xl leading-tight font-sans tracking-tight">
                    Doanh Nghiệp Bất Động Sản
                </h1>
                <p class="text-slate-300 text-sm sm:text-base font-medium max-w-lg">
                    Danh sách các chủ đầu tư, tập đoàn phát triển & phân phối bất động sản uy tín hàng đầu tại Việt Nam.
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        
        <!-- Search and Filter Bar -->
        <div class="flex flex-col sm:flex-row gap-4 justify-between items-center bg-slate-50 p-4 rounded-3xl border border-slate-100 shadow-premium">
            <form action="{{ route('enterprises.index') }}" method="GET" class="w-full sm:max-w-md flex gap-2">
                <div class="relative flex-grow bg-white border border-slate-200/60 rounded-2xl px-4 py-3 flex items-center gap-2 shadow-2xs">
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Tìm theo tên doanh nghiệp hoặc địa chỉ..." class="w-full bg-transparent border-0 p-0 text-slate-800 placeholder-slate-400 font-bold focus:outline-none focus:ring-0 text-xs">
                </div>
                <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-bold px-6 py-3 rounded-2xl transition-all btn-hover-premium text-xs">
                    Tìm
                </button>
            </form>

            <div class="text-xs text-slate-400 font-semibold shrink-0">
                Hiển thị <span class="text-slate-800 font-extrabold">{{ $enterprises->count() }}</span> doanh nghiệp trên trang này
            </div>
        </div>

        @if($enterprises->isEmpty())
            <div class="text-center py-20 bg-slate-50 rounded-[32px] border border-slate-100/50 space-y-4">
                <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto border border-slate-200/50">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                </div>
                <h3 class="font-extrabold text-slate-800 text-base">Không tìm thấy doanh nghiệp phù hợp</h3>
                <p class="text-xs text-slate-400 max-w-sm mx-auto">Vui lòng điều chỉnh lại từ khóa tìm kiếm hoặc bấm đặt lại bộ lọc để tải danh sách mặc định.</p>
                <a href="{{ route('enterprises.index') }}" class="inline-block bg-primary/10 text-primary font-black px-5 py-2.5 rounded-xl text-xs hover:bg-primary hover:text-white transition-all">Đặt lại bộ lọc</a>
            </div>
        @else
            <!-- Enterprises Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($enterprises as $ent)
                    <div class="bg-white rounded-[32px] border border-slate-100/80 shadow-premium card-hover-premium overflow-hidden flex flex-col justify-between group p-6 relative">
                        
                        <div class="space-y-6">
                            <!-- Logo and Info Header -->
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-2xl overflow-hidden shadow-md border border-slate-100 bg-white p-1 shrink-0">
                                    <img src="{{ $ent->logo ?? 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&q=80&w=200' }}" 
                                         alt="{{ $ent->name }}" 
                                         class="w-full h-full object-cover">
                                </div>
                                <div class="space-y-1">
                                    <span class="bg-primary-extralight text-primary text-[9px] font-black px-2.5 py-0.5 rounded-[6px] uppercase tracking-wider">
                                        Chủ đầu tư
                                    </span>
                                    <h3 class="text-sm font-extrabold text-slate-800 line-clamp-1 group-hover:text-primary transition-colors duration-300" title="{{ $ent->name }}">
                                        {{ $ent->name }}
                                    </h3>
                                </div>
                            </div>

                            <!-- Description summary -->
                            <p class="text-xs text-slate-400 leading-relaxed line-clamp-3">
                                {{ strip_tags($ent->description) }}
                            </p>

                            <!-- Mini facts / metadata grid -->
                            <div class="grid grid-cols-2 gap-3 pt-3 border-t border-slate-50 text-[10px] font-bold text-slate-500">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <span>Thành lập: <span class="text-slate-700 font-extrabold">{{ $ent->founded_year ?? 'Chưa rõ' }}</span></span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                    <span>Dự án đăng ký: <span class="text-primary font-black">{{ $ent->api_properties_count ?? $ent->properties->count() }} BĐS</span></span>
                                </div>
                            </div>

                            <!-- Address info -->
                            <div class="flex items-start gap-2 pt-2 text-[10px] text-slate-400 font-medium leading-relaxed">
                                <svg class="w-4 h-4 text-slate-300 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                <span class="line-clamp-2" title="{{ $ent->address }}">{{ $ent->address }}</span>
                            </div>
                        </div>

                        <!-- Card Action -->
                        <div class="mt-6 pt-4 border-t border-slate-100 flex justify-between items-center">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">MST: {{ $ent->tax_code ?? 'Đang cập nhật' }}</span>
                            
                            <a href="{{ route('enterprises.show', $ent->slug) }}" 
                               class="bg-primary hover:bg-primary-dark text-white font-extrabold text-[11px] px-5 py-2.5 rounded-xl transition-all btn-hover-premium flex items-center gap-1.5">
                                Xem chi tiết
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Custom Styled Pagination -->
            <div class="pt-10 flex justify-center">
                {{ $enterprises->links() }}
            </div>
        @endif

    </div>

</div>
@endsection
