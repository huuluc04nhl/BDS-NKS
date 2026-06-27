@extends('layouts.app')

@section('title', $post->title . ' | BDS NKS')

@section('content')
<div class="bg-white pb-20">
    
    <!-- Breadcrumbs Navigation -->
    <nav class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
        <ol class="flex items-center gap-1.5 text-xs text-slate-400 font-semibold uppercase tracking-wider">
            <li>
                <a href="/" class="hover:text-primary transition-colors">Trang chủ</a>
            </li>
            <li class="flex items-center gap-1">
                <svg class="w-3 h-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                <a href="{{ route('news.index') }}" class="hover:text-primary transition-colors">Tin tức</a>
            </li>
            <li class="flex items-center gap-1">
                <svg class="w-3 h-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                <a href="{{ route('news.index', ['category' => $post->category]) }}" class="text-primary font-black">
                    @switch($post->category)
                        @case('report') Báo cáo thị trường @break
                        @case('view') Góc nhìn NKS @break
                        @case('interior') Nội thất @break
                        @case('fengshui') Phong thủy @break
                        @case('news') Tin tức @break
                        @case('knowledge') Kiến thức @break
                    @endswitch
                </a>
            </li>
        </ol>
    </nav>

    <!-- Main Article Body -->
    <article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 space-y-8">
        
        <!-- Header Info -->
        <div class="space-y-4">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 leading-tight tracking-tight font-sans">
                {{ $post->title }}
            </h1>
            
            <div class="flex items-center gap-3 pt-2 text-xs text-slate-400 font-bold border-b border-slate-100 pb-6">
                <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200/50 flex items-center justify-center text-slate-500 font-extrabold text-[10px]">
                    NKS
                </div>
                <div>
                    <span class="text-slate-700 block">Ban Biên Tập BDS NKS</span>
                    <span class="text-[10px] text-slate-400 block font-semibold mt-0.5">Đăng ngày {{ $post->created_at ? $post->created_at->format('d/m/Y') : 'Vừa đăng' }} • 3 phút đọc</span>
                </div>
            </div>
        </div>

        <!-- Spotlight Image banner -->
        <div class="rounded-[32px] overflow-hidden h-[340px] sm:h-[420px] shadow-sm border border-slate-100">
            <img src="{{ $post->feature_img }}" 
                 alt="{{ $post->title }}" 
                 class="w-full h-full object-cover">
        </div>

        <!-- Article Summary Block -->
        @if($post->summary)
            <div class="p-6 bg-slate-50 rounded-3xl border-l-4 border-primary text-slate-600 text-sm font-bold leading-relaxed shadow-3xs">
                {{ $post->summary }}
            </div>
        @endif

        <!-- Rendered Content Box (Styled Markdown Reader Mode) -->
        <div class="prose max-w-none text-slate-600 text-sm sm:text-base leading-relaxed space-y-6">
            {!! \Illuminate\Support\Str::markdown(str_replace('\n', "\n", $post->content ?? '')) !!}
        </div>

        <!-- Social Share Bar (Interactive) -->
        <div class="pt-8 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="text-xs text-slate-400 font-bold">Chia sẻ bài viết này:</div>
            
            <div class="flex flex-wrap gap-2.5">
                <!-- Share Facebook -->
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                   target="_blank"
                   class="flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-100 hover:border-blue-100 hover:bg-blue-50/20 text-xs font-bold text-slate-600 hover:text-blue-600 transition-all shadow-2xs">
                    <svg class="w-4 h-4 fill-currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c4.56-.93 8-4.96 8-9.75z"/></svg>
                    Facebook
                </a>

                <!-- Share Zalo -->
                <a href="https://sp.zalo.me/share_to_zalo?url={{ urlencode(request()->url()) }}" 
                   target="_blank"
                   class="flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-100 hover:border-sky-100 hover:bg-sky-50/20 text-xs font-bold text-slate-600 hover:text-sky-600 transition-all shadow-2xs">
                    Zalo
                </a>

                <!-- Copy link -->
                <button @click="navigator.clipboard.writeText('{{ request()->url() }}'); alert('Đã sao chép liên kết bài viết!')"
                        class="flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-100 hover:border-slate-300 text-xs font-bold text-slate-600 hover:text-slate-800 transition-all shadow-2xs">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" /></svg>
                    Sao chép liên kết
                </button>
            </div>
        </div>

    </article>

    <!-- Bottom Related Articles Section -->
    @if(!$related->isEmpty())
        <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-20 pt-10 border-t border-slate-100 space-y-8">
            <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Bài viết liên quan</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                @foreach($related as $rel)
                    <div class="space-y-3 group cursor-pointer"
                         @click="window.location.href = '{{ route('news.show', $rel->slug ?? $rel->id) }}'">
                        
                        <div class="h-32 rounded-2xl overflow-hidden shadow-2xs border border-slate-100 relative">
                            <img src="{{ $rel->feature_img }}" 
                                 alt="{{ $rel->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-500">
                        </div>
                        
                        <div class="space-y-1">
                            <h4 class="text-xs font-bold text-slate-800 group-hover:text-primary transition-colors leading-snug line-clamp-2">
                                {{ $rel->title }}
                            </h4>
                            <span class="text-[9px] text-slate-400 font-bold block">{{ $rel->created_at ? $rel->created_at->format('d/m/Y') : 'Vừa đăng' }}</span>
                        </div>

                    </div>
                @endforeach
            </div>
        </section>
    @endif

</div>
@endsection
