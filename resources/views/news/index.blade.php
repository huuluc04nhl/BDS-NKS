@extends('layouts.app')

@section('title', 'Wiki Bất Động Sản - Tin Tức, Báo Cáo & Phong Thủy | BDS NKS')

@section('content')
<div class="space-y-12 pb-20 bg-white">
    
    <!-- Hero / Header Title Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
        <div class="space-y-2 border-b border-slate-100 pb-6">
            <span class="text-xs font-black text-primary uppercase tracking-widest">NKS WIKI TIN TỨC</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-800 tracking-tight font-sans">
                Tin Tức Bất Động Sản
            </h1>
            <p class="text-slate-400 text-xs sm:text-sm font-medium">
                Cập nhật nhanh chóng xu hướng thị trường, kiến thức đầu tư, thiết kế nội thất và cẩm nang phong thủy.
            </p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">
            
            <!-- Main Content Area (Spotlight & Grid) -->
            <div class="lg:col-span-2 space-y-12">
                
                <!-- Spotlight Featured Post (If available) -->
                @if($spotlight)
                    <div class="relative rounded-[36px] overflow-hidden shadow-premium group cursor-pointer border border-slate-100/50 h-[380px] sm:h-[440px]"
                         @click="window.location.href = '{{ route('news.show', $spotlight->slug ?? $spotlight->id) }}'">
                        
                        <!-- Image with Zoom effect -->
                        <img src="{{ $spotlight->feature_img }}" 
                             alt="{{ $spotlight->title }}" 
                             class="absolute inset-0 w-full h-full object-cover group-hover:scale-102 transition-transform duration-700 ease-out">
                        
                        <!-- Dark overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/40 to-transparent"></div>

                        <!-- Card text content -->
                        <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-10 space-y-4 text-white z-10">
                            <!-- Category Badge -->
                            <span class="inline-block bg-primary text-white text-[9px] font-black px-3 py-1 rounded-[6px] uppercase tracking-wider">
                                @switch($spotlight->category)
                                    @case('report') Báo cáo thị trường @break
                                    @case('view') Góc nhìn NKS @break
                                    @case('interior') Nội thất @break
                                    @case('fengshui') Phong thủy @break
                                    @case('news') Tin tức @break
                                    @case('knowledge') Kiến thức @break
                                    @default Tin tức BĐS
                                @endswitch
                            </span>

                            <h2 class="text-xl sm:text-3xl font-extrabold line-clamp-2 hover:underline tracking-tight leading-snug">
                                {{ $spotlight->title }}
                            </h2>

                            <p class="text-white/80 text-xs font-semibold leading-relaxed line-clamp-2 max-w-xl">
                                {{ $spotlight->summary }}
                            </p>

                            <div class="flex items-center justify-between pt-2 text-[10px] text-white/60 font-bold border-t border-white/10">
                                <span>{{ $spotlight->created_at ? $spotlight->created_at->format('d/m/Y') : 'Vừa đăng' }}</span>
                                <span class="flex items-center gap-1">Đọc bài viết <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg></span>
                            </div>
                        </div>

                    </div>
                @endif

                <!-- Category Filtering Navigation Menu -->
                <div class="flex flex-wrap gap-2 border-b border-slate-100 pb-4">
                    <a href="{{ route('news.index', ['category' => 'report']) }}" class="px-5 py-2.5 rounded-2xl text-xs font-black transition-all {{ $category === 'report' ? 'bg-primary text-white shadow-sm' : 'bg-slate-50 text-slate-500 hover:bg-slate-100' }}">Báo cáo Thị trường BĐS</a>
                    <a href="{{ route('news.index', ['category' => 'view']) }}" class="px-5 py-2.5 rounded-2xl text-xs font-black transition-all {{ $category === 'view' ? 'bg-primary text-white shadow-sm' : 'bg-slate-50 text-slate-500 hover:bg-slate-100' }}">Góc Nhìn NKS</a>
                    <a href="{{ route('news.index', ['category' => 'interior']) }}" class="px-5 py-2.5 rounded-2xl text-xs font-black transition-all {{ $category === 'interior' ? 'bg-primary text-white shadow-sm' : 'bg-slate-50 text-slate-500 hover:bg-slate-100' }}">Nội Thất</a>
                    <a href="{{ route('news.index', ['category' => 'fengshui']) }}" class="px-5 py-2.5 rounded-2xl text-xs font-black transition-all {{ $category === 'fengshui' ? 'bg-primary text-white shadow-sm' : 'bg-slate-50 text-slate-500 hover:bg-slate-100' }}">Phong Thủy</a>
                    <a href="{{ route('news.index', ['category' => 'news']) }}" class="px-5 py-2.5 rounded-2xl text-xs font-black transition-all {{ $category === 'news' ? 'bg-primary text-white shadow-sm' : 'bg-slate-50 text-slate-500 hover:bg-slate-100' }}">Tin Tức</a>
                    <a href="{{ route('news.index', ['category' => 'knowledge']) }}" class="px-5 py-2.5 rounded-2xl text-xs font-black transition-all {{ $category === 'knowledge' ? 'bg-primary text-white shadow-sm' : 'bg-slate-50 text-slate-500 hover:bg-slate-100' }}">Kiến Thức</a>
                </div>

                <!-- Articles Grid -->
                @if($posts->isEmpty())
                    <div class="text-center py-16 bg-slate-50 rounded-[32px] border border-slate-100/50 text-slate-400 text-xs font-bold">
                        Không có bài viết nào khác trong danh mục này.
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                        @foreach($posts as $post)
                            <div class="space-y-4 group cursor-pointer"
                                 @click="window.location.href = '{{ route('news.show', $post->slug ?? $post->id) }}'">
                                
                                <div class="h-48 rounded-[24px] overflow-hidden shadow-2xs relative border border-slate-100/60">
                                    <img src="{{ $post->feature_img }}" 
                                         alt="{{ $post->title }}" 
                                         class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent"></div>
                                </div>
                                
                                <div class="space-y-2">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                        @switch($post->category)
                                            @case('report') Báo cáo @break
                                            @case('view') Góc nhìn @break
                                            @case('interior') Nội thất @break
                                            @case('fengshui') Phong thủy @break
                                            @case('news') Tin tức @break
                                            @case('knowledge') Kiến thức @break
                                        @endswitch
                                    </span>
                                    <h4 class="text-sm font-extrabold text-slate-800 group-hover:text-primary transition-colors leading-snug line-clamp-2">
                                        {{ $post->title }}
                                    </h4>
                                    <p class="text-xs text-slate-400 font-medium line-clamp-2">
                                        {{ $post->summary }}
                                    </p>
                                    <p class="text-[10px] text-slate-400 font-bold pt-1">
                                        {{ $post->created_at ? $post->created_at->format('d/m/Y') : 'Vừa đăng' }}
                                    </p>
                                </div>

                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="pt-6 flex justify-center">
                        {{ $posts->links() }}
                    </div>
                @endif

            </div>

            <!-- Sidebar Column -->
            <div class="space-y-8">
                
                <!-- Search Box Widget -->
                <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100 shadow-premium space-y-4">
                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider block">Tìm kiếm tin tức</h3>
                    <form action="{{ route('news.index') }}" method="GET" class="flex gap-2">
                        <input type="hidden" name="category" value="{{ $category }}">
                        <div class="relative flex-grow bg-white border border-slate-200/60 rounded-2xl px-3 py-2.5 flex items-center gap-2 shadow-2xs">
                            <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Nhập từ khóa tìm kiếm..." class="w-full bg-transparent border-0 p-0 text-slate-700 placeholder-slate-400 font-bold focus:outline-none focus:ring-0 text-xs">
                        </div>
                        <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-bold px-4 py-2.5 rounded-2xl transition-all text-xs">
                            Tìm
                        </button>
                    </form>
                </div>

                <!-- Popular Articles Widget (Tin đọc nhiều) -->
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-premium space-y-6">
                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest pb-3 border-b border-slate-50">
                        Tin đọc nhiều
                    </h3>
                    
                    <div class="space-y-4">
                        @foreach($popular as $index => $pop)
                            <div class="flex gap-3 items-start group cursor-pointer"
                                 @click="window.location.href = '{{ route('news.show', $pop->slug ?? $pop->id) }}'">
                                <div class="w-14 h-14 rounded-xl overflow-hidden border border-slate-100 shrink-0">
                                    <img src="{{ $pop->feature_img }}" 
                                         alt="{{ $pop->title }}" 
                                         class="w-full h-full object-cover group-hover:scale-103 transition-transform">
                                </div>
                                <div class="space-y-1">
                                    <h4 class="text-xs font-bold text-slate-800 leading-snug line-clamp-2 group-hover:text-primary transition-colors">
                                        {{ $pop->title }}
                                    </h4>
                                    <span class="text-[9px] text-slate-400 font-bold block">{{ $pop->created_at ? $pop->created_at->format('d/m/Y') : 'Vừa đăng' }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>
@endsection
