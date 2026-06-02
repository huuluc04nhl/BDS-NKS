@extends('layouts.app')

@section('title', 'BDS NKS - Website Cho Thuê Bất Động Sản Chính Chủ TPHCM')

@section('content')
<div class="space-y-28 pb-28 bg-white"
     x-data="{
         favorites: [],
         activeTab: 'rent',
         init() {
             const saved = localStorage.getItem('nks_favorites');
             if (saved) {
                 this.favorites = JSON.parse(saved);
             }
             window.addEventListener('nks-fav-change', () => {
                 const current = localStorage.getItem('nks_favorites');
                 this.favorites = current ? JSON.parse(current) : [];
             });
         },
         isFav(id) {
             return this.favorites.some(f => f.id === id);
         },
         toggleFav(property) {
             const index = this.favorites.findIndex(f => f.id === property.id);
             if (index > -1) {
                 this.favorites.splice(index, 1);
             } else {
                 this.favorites.push({
                     id: property.id,
                     title: property.title,
                     slug: property.slug,
                     featureimg: property.featureimg,
                     address: property.address,
                     rstype: property.rstype,
                     formatedPrice: property.formatedPrice
                 });
             }
             localStorage.setItem('nks_favorites', JSON.stringify(this.favorites));
             window.dispatchEvent(new CustomEvent('nks-fav-change'));
             window.dispatchEvent(new CustomEvent('nks-login-change'));
         }
     }">
    
    <!-- Hero / Banner Section: Full-Width Cityscape Landscape (Exact Moso Screenshot 1 UX) -->
    <section class="relative h-[560px] flex items-center justify-start overflow-hidden">
        <!-- Landscape Background Image with Overlay -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&q=80&w=1920" 
                 alt="Cityscape HCMC" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/80 via-slate-900/40 to-transparent"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="max-w-2xl text-left space-y-6 animate-fade-in-up">
                <h1 class="text-white font-extrabold text-5xl sm:text-6xl lg:text-7xl leading-tight font-sans tracking-tight">
                    Tìm Nhà<br>Thấy Địa Chỉ & Giá
                </h1>
                <p class="text-white/90 text-base md:text-lg font-medium">
                    Nền tảng bất động sản minh bạch
                </p>

                <!-- Clean Pure White Search Bar -->
                <form action="/properties" method="GET" class="bg-white rounded-[20px] p-2 flex items-center shadow-lg border border-slate-100/50 mt-8 max-w-xl">
                    <div class="flex items-center gap-2 px-3 flex-grow">
                        <!-- Map Pin Icon -->
                        <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        <input type="text" name="kw" placeholder="Nhập địa chỉ hoặc khu vực tìm kiếm" class="w-full bg-transparent border-0 p-0 text-slate-800 placeholder-slate-400 font-bold focus:outline-none focus:ring-0 text-sm">
                    </div>
                    <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-bold px-6 py-3.5 rounded-[14px] flex items-center gap-1.5 transition-all text-xs">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        Tìm kiếm
                    </button>
                </form>

                <!-- Search Shortcut Tags Under Search Input -->
                <div class="flex flex-wrap gap-2 pt-2">
                    <a href="/properties?kw=Lê Văn Sỹ" class="bg-white/90 hover:bg-white text-[10px] font-extrabold text-primary rounded-full px-4 py-1.5 shadow-sm transition-all uppercase tracking-wide">Lê Văn Sỹ</a>
                    <a href="/properties?kw=Vinhomes" class="bg-white/90 hover:bg-white text-[10px] font-extrabold text-primary rounded-full px-4 py-1.5 shadow-sm transition-all uppercase tracking-wide">Vinhomes Central Park</a>
                    <a href="/properties?kw=Thảo Điền" class="bg-white/90 hover:bg-white text-[10px] font-extrabold text-primary rounded-full px-4 py-1.5 shadow-sm transition-all uppercase tracking-wide">Thảo Điền</a>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION: NHÀ ĐẤT NỔI BẬT (Exact Moso Screenshot 2 UX - Branded to Primary Blue #0077bb) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <div class="flex justify-between items-end">
            <h2 class="text-2xl font-black text-slate-900">Nhà đất nổi bật</h2>
            <!-- Slider Buttons -->
            <div class="flex gap-2">
                <button class="w-9 h-9 rounded-full bg-slate-50 border border-slate-100 hover:bg-slate-100 flex items-center justify-center text-slate-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                </button>
                <button class="w-9 h-9 rounded-full bg-slate-50 border border-slate-100 hover:bg-slate-100 flex items-center justify-center text-slate-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </button>
            </div>
        </div>

        <!-- 3-Column Premium Property Card Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach(collect($featuredRentals)->take(3) as $index => $property)
                <div class="bg-white rounded-[24px] border border-slate-100 hover:shadow-premium shadow-sm transition-custom-all duration-500 overflow-hidden flex flex-col group p-3 pb-5">
                    
                    <!-- Image frame padding exactly like Moso screenshot -->
                    <div class="h-60 rounded-[18px] overflow-hidden relative">
                        <img src="{{ $property['featureimg'] ?? 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&q=80&w=800' }}" 
                             alt="{{ $property['title'] }}" 
                             class="w-full h-full object-cover group-hover:scale-[1.05] transition-transform duration-700 ease-out">
                        
                        <!-- Floating badges -->
                        <div class="absolute top-3 left-3 flex gap-1.5 items-center">
                            <span class="px-2.5 py-1 rounded-[8px] text-[9px] font-black tracking-wider bg-primary text-white uppercase shadow-sm">
                                Đang bán
                            </span>
                            <span class="px-2 py-1 rounded-[8px] text-[9px] font-bold bg-black/40 text-white backdrop-blur-sm">
                                {{ $index + 3 }} ngày trước
                            </span>
                        </div>
                        
                        <!-- Favorite Toggle -->
                        <button @click="toggleFav({{ json_encode($property) }})" 
                                class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/90 text-slate-600 hover:bg-white hover:text-red-500 flex items-center justify-center shadow-md transition-all duration-200"
                                :class="isFav({{ $property['id'] }}) && 'bg-red-500 text-white'">
                            <svg class="w-4 h-4" :fill="isFav({{ $property['id'] }}) ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                        </button>
                    </div>

                    <!-- Content -->
                    <div class="px-2 pt-4 flex-grow flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <!-- Category and Verified Badges -->
                            <div class="flex gap-2">
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-bold px-2.5 py-0.5 rounded-[8px]">
                                    Nhà mặt tiền
                                </span>
                                <span class="text-emerald-600 bg-emerald-50 text-[10px] font-extrabold px-2.5 py-0.5 rounded-[8px] flex items-center gap-0.5">
                                    <svg class="w-3 h-3 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    Xác thực
                                </span>
                            </div>

                            <!-- Pricing: Branded Pricing & Area -->
                            <div class="flex justify-between items-end">
                                <span class="text-lg font-black text-primary leading-none">
                                    {{ $property['formatedPrice'] }}
                                </span>
                                <span class="text-[11px] font-bold text-slate-400">
                                    {{ $property['formatedSqrPrice'] ?? '161,6triệu' }}
                                </span>
                            </div>

                            <!-- Title & Address -->
                            <a href="/properties/{{ $property['slug'] }}" class="block font-bold text-slate-800 hover:text-primary transition-colors text-sm line-clamp-1">
                                {{ $property['title'] }}
                            </a>
                        </div>

                        <!-- Bed, Bath, Floor, Area Attributes border-to-border layout -->
                        <div class="flex items-center justify-between text-slate-500 text-[11px] font-semibold pt-3 border-t border-slate-100">
                            <span class="flex items-center gap-1"><svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg> 5</span>
                            <span class="flex items-center gap-1"><svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg> 6</span>
                            <span class="flex items-center gap-1"><svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg> 5</span>
                            <span class="flex items-center gap-1"><svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 01.553-.894L9 2l6 3 5.447-2.724A1 1 0 0121 3.168v10.764a1 1 0 01-.553.894L15 18l-6 2z" /></svg> 80m²</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- SECTION: NHÀ ĐẤT CHO THUÊ (Exact Moso Screenshot 3 UX) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <div class="flex justify-between items-end">
            <h2 class="text-2xl font-black text-slate-900">Nhà đất cho thuê</h2>
            <!-- Slider Buttons -->
            <div class="flex gap-2">
                <button class="w-9 h-9 rounded-full bg-slate-50 border border-slate-100 hover:bg-slate-100 flex items-center justify-center text-slate-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                </button>
                <button class="w-9 h-9 rounded-full bg-slate-50 border border-slate-100 hover:bg-slate-100 flex items-center justify-center text-slate-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($featuredRentals as $index => $property)
                <div class="bg-white rounded-[24px] border border-slate-100 hover:shadow-premium shadow-sm transition-custom-all duration-500 overflow-hidden flex flex-col group p-3 pb-5 animate-fade-in-up"
                     style="animation-delay: {{ $index * 0.1 }}s;">
                    
                    <!-- Image aspect video -->
                    <div class="h-60 rounded-[18px] overflow-hidden relative">
                        <img src="{{ $property['featureimg'] ?? 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&q=80&w=800' }}" 
                             alt="{{ $property['title'] }}" 
                             class="w-full h-full object-cover group-hover:scale-[1.05] transition-transform duration-700 ease-out">
                        
                        <!-- Badges -->
                        <div class="absolute top-3 left-3 flex gap-1.5 items-center">
                            <span class="px-2.5 py-1 rounded-[8px] text-[9px] font-black tracking-wider bg-primary text-white uppercase shadow-sm">
                                Cho thuê
                            </span>
                            <span class="px-2 py-1 rounded-[8px] text-[9px] font-bold bg-black/40 text-white backdrop-blur-sm" x-text="'{{ $index }}' === '0' ? 'vừa đăng' : '{{ $index }} giờ trước'">
                            </span>
                        </div>
                        
                        <!-- Favorite Toggle -->
                        <button @click="toggleFav({{ json_encode($property) }})" 
                                class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/90 text-slate-600 hover:bg-white hover:text-red-500 flex items-center justify-center shadow-md transition-all duration-200"
                                :class="isFav({{ $property['id'] }}) && 'bg-red-500 text-white'">
                            <svg class="w-4 h-4" :fill="isFav({{ $property['id'] }}) ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                        </button>
                    </div>

                    <!-- Content -->
                    <div class="px-2 pt-4 flex-grow flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <!-- Category and Verified Badges -->
                            <div class="flex gap-2">
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-bold px-2.5 py-0.5 rounded-[8px]">
                                    {{ $property['rstype'] }}
                                </span>
                                <span class="text-emerald-600 bg-emerald-50 text-[10px] font-extrabold px-2.5 py-0.5 rounded-[8px] flex items-center gap-0.5">
                                    <svg class="w-3 h-3 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    Xác thực
                                </span>
                            </div>

                            <!-- Pricing: Huge Blue Text & Area -->
                            <div class="flex justify-between items-end">
                                <span class="text-lg font-black text-primary leading-none">
                                    {{ $property['formatedPrice'] }}
                                </span>
                                <span class="text-[11px] font-bold text-slate-400" x-text="'{{ $property['formatedSqrPrice'] }}' || '{{ number_format($property['total_area'], 0) }}m²'">
                                </span>
                            </div>

                            <!-- Title & Address -->
                            <a href="/properties/{{ $property['slug'] }}" class="block font-bold text-slate-800 hover:text-primary transition-colors text-sm line-clamp-1">
                                {{ $property['title'] }}
                            </a>
                        </div>

                        <!-- Bed, Bath, Floor, Area Attributes border-to-border layout -->
                        <div class="flex items-center justify-between text-slate-500 text-[11px] font-semibold pt-3 border-t border-slate-100">
                            <span class="flex items-center gap-1"><svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg> {{ $property['bed'] ?? 1 }}</span>
                            <span class="flex items-center gap-1"><svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg> {{ $property['bath'] ?? 1 }}</span>
                            <span class="flex items-center gap-1"><svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg> {{ $property['floors'] ?? 1 }}</span>
                            <span class="flex items-center gap-1"><svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 01.553-.894L9 2l6 3 5.447-2.724A1 1 0 0121 3.168v10.764a1 1 0 01-.553.894L15 18l-6 2z" /></svg> {{ number_format($property['total_area'], 0) }}m²</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-slate-400">
                    Đang kết nối tải dữ liệu từ API...
                </div>
            @endforelse
        </div>
    </section>

    <!-- SECTION: NHU CẦU CỘNG ĐỒNG (Exact Moso Screenshot 4 UX - Branded to Primary Blue #0077bb) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-slate-50/70 rounded-[36px] p-10 border border-slate-100 shadow-sm space-y-8 relative overflow-hidden">
            <!-- Header -->
            <div class="flex justify-between items-start md:items-end gap-4 relative z-10">
                <div class="space-y-2">
                    <h2 class="text-2xl font-black text-slate-900">Nhu cầu</h2>
                    <p class="text-xs text-slate-500 font-medium">Khám phá nhu cầu mua, bán, thuê mới nhất từ cộng đồng</p>
                </div>
                <!-- Slider Buttons -->
                <div class="flex gap-2">
                    <button class="w-8 h-8 rounded-full bg-white hover:bg-slate-50 flex items-center justify-center text-slate-600 transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                    </button>
                    <button class="w-8 h-8 rounded-full bg-white hover:bg-slate-50 flex items-center justify-center text-slate-600 transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </button>
                </div>
            </div>

            <!-- Horizontal grid slider -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 relative z-10">
                <!-- Create Demand Button -->
                <a href="/profile?tab=host" class="bg-white rounded-[24px] p-6 border-2 border-dashed border-primary/20 hover:border-primary transition-colors flex flex-col items-center justify-center text-center space-y-3 min-h-[170px] shadow-sm">
                    <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center shadow-md">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-black text-slate-800">Tạo nhu cầu</h4>
                        <p class="text-[10px] text-slate-400 mt-1">Chia sẻ điều bạn đang tìm</p>
                    </div>
                </a>

                <!-- Demand 1 -->
                <div class="bg-white rounded-[24px] p-6 border border-slate-100 shadow-sm flex flex-col justify-between min-h-[170px] transition-custom-all hover:scale-102">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <div class="w-8 h-8 rounded-full bg-primary/10 text-primary font-extrabold text-[11px] flex items-center justify-center">
                                DP
                            </div>
                            <span class="bg-blue-50 text-blue-600 text-[9px] font-black tracking-widest px-2.5 py-1 rounded-[6px] uppercase">
                                Cho thuê
                            </span>
                        </div>
                        <h4 class="text-xs font-bold text-slate-800 leading-snug line-clamp-3">
                            Cho thuê căn hộ chung cư Hoàng văn thụ Quận Tân...
                        </h4>
                    </div>
                    <div class="flex justify-between items-center text-[10px] text-slate-400 pt-2 border-t border-slate-50 font-medium">
                        <span>17 giờ trước</span>
                        <span>Quận Tân Bình, Thàn...</span>
                    </div>
                </div>

                <!-- Demand 2 -->
                <div class="bg-white rounded-[24px] p-6 border border-slate-100 shadow-sm flex flex-col justify-between min-h-[170px] transition-custom-all hover:scale-102">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <div class="w-8 h-8 rounded-full bg-primary/10 text-primary font-extrabold text-[11px] flex items-center justify-center">
                                QA
                            </div>
                            <span class="bg-blue-50 text-blue-600 text-[9px] font-black tracking-widest px-2.5 py-1 rounded-[6px] uppercase">
                                Cho thuê
                            </span>
                        </div>
                        <h4 class="text-xs font-bold text-slate-800 leading-snug line-clamp-3">
                            Cho thuê nhà hẻm Long Thuận Quận 9, TP.HCM – 7...
                        </h4>
                    </div>
                    <div class="flex justify-between items-center text-[10px] text-slate-400 pt-2 border-t border-slate-50 font-medium">
                        <span>3 ngày trước</span>
                        <span>Quận 9, Thành phố H...</span>
                    </div>
                </div>

                <!-- Demand 3 -->
                <div class="bg-white rounded-[24px] p-6 border border-slate-100 shadow-sm flex flex-col justify-between min-h-[170px] transition-custom-all hover:scale-102">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <div class="w-8 h-8 rounded-full bg-primary/10 text-primary font-extrabold text-[11px] flex items-center justify-center">
                                TT
                            </div>
                            <span class="bg-blue-50 text-blue-600 text-[9px] font-black tracking-widest px-2.5 py-1 rounded-[6px] uppercase">
                                Cho thuê
                            </span>
                        </div>
                        <h4 class="text-xs font-bold text-slate-800 leading-snug line-clamp-3">
                            Cho thuê phòng trọ Đường Đỗ Đốc Chấn Quận Tân...
                        </h4>
                    </div>
                    <div class="flex justify-between items-center text-[10px] text-slate-400 pt-2 border-t border-slate-50 font-medium">
                        <span>3 ngày trước</span>
                        <span>Quận Tân Phú, Thàn...</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION: VIDEO NHÀ ĐẤT (Exact Moso Screenshot 4 & 5 Video Cards) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <div class="flex justify-between items-end">
            <h2 class="text-2xl font-black text-slate-900">Video nhà đất</h2>
            <!-- Slider Buttons -->
            <div class="flex gap-2">
                <button class="w-9 h-9 rounded-full bg-slate-50 border border-slate-100 hover:bg-slate-100 flex items-center justify-center text-slate-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                </button>
                <button class="w-9 h-9 rounded-full bg-slate-50 border border-slate-100 hover:bg-slate-100 flex items-center justify-center text-slate-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
            <!-- Video 1 -->
            <div class="rounded-[24px] overflow-hidden h-[360px] relative group shadow-premium hover:shadow-xl transition-all duration-500 cursor-pointer">
                <img src="https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?auto=format&fit=crop&q=80&w=600" alt="Video Review" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent"></div>
                <!-- Play Icon overlay -->
                <div class="absolute inset-0 flex items-center justify-center text-white opacity-90 group-hover:opacity-100 transition-opacity">
                    <div class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-md border border-white/40 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 fill-white" viewBox="0 0 24 24"><path d="M8 5v14l11-7z" /></svg>
                    </div>
                </div>
                <div class="absolute top-4 left-4">
                    <span class="bg-primary text-white text-[9px] font-black px-2.5 py-1 rounded-[6px] uppercase shadow-sm">
                        Cho thuê
                    </span>
                </div>
                <div class="absolute bottom-4 left-4 right-4 text-white space-y-1">
                    <h4 class="text-xs font-extrabold line-clamp-2">Căn hộ Landmark 81 Full nội thất view sông</h4>
                    <p class="text-[9px] text-slate-300 font-medium">Bình Thạnh, TPHCM</p>
                </div>
            </div>

            <!-- Video 2 -->
            <div class="rounded-[24px] overflow-hidden h-[360px] relative group shadow-premium hover:shadow-xl transition-all duration-500 cursor-pointer">
                <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&q=80&w=600" alt="Video Review" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent"></div>
                <div class="absolute inset-0 flex items-center justify-center text-white opacity-90 group-hover:opacity-100 transition-opacity">
                    <div class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-md border border-white/40 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 fill-white" viewBox="0 0 24 24"><path d="M8 5v14l11-7z" /></svg>
                    </div>
                </div>
                <div class="absolute top-4 left-4">
                    <span class="bg-primary text-white text-[9px] font-black px-2.5 py-1 rounded-[6px] uppercase shadow-sm">
                        Đang bán
                    </span>
                </div>
                <div class="absolute bottom-4 left-4 right-4 text-white space-y-1">
                    <h4 class="text-xs font-extrabold line-clamp-2">Nhà phố mặt tiền kinh doanh 222 Lê Văn Sỹ</h4>
                    <p class="text-[9px] text-slate-300 font-medium">Phú Nhuận, TPHCM</p>
                </div>
            </div>

            <!-- Video 3 -->
            <div class="rounded-[24px] overflow-hidden h-[360px] relative group shadow-premium hover:shadow-xl transition-all duration-500 cursor-pointer">
                <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&q=80&w=600" alt="Video Review" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent"></div>
                <div class="absolute inset-0 flex items-center justify-center text-white opacity-90 group-hover:opacity-100 transition-opacity">
                    <div class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-md border border-white/40 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 fill-white" viewBox="0 0 24 24"><path d="M8 5v14l11-7z" /></svg>
                    </div>
                </div>
                <div class="absolute top-4 left-4">
                    <span class="bg-primary text-white text-[9px] font-black px-2.5 py-1 rounded-[6px] uppercase shadow-sm">
                        Đang bán
                    </span>
                </div>
                <div class="absolute bottom-4 left-4 right-4 text-white space-y-1">
                    <h4 class="text-xs font-extrabold line-clamp-2">Biệt thự song lập compound Thảo Điền Quận 2</h4>
                    <p class="text-[9px] text-slate-300 font-medium">Quận 2, TPHCM</p>
                </div>
            </div>

            <!-- Video 4 -->
            <div class="rounded-[24px] overflow-hidden h-[360px] relative group shadow-premium hover:shadow-xl transition-all duration-500 cursor-pointer">
                <img src="https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?auto=format&fit=crop&q=80&w=600" alt="Video Review" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent"></div>
                <div class="absolute inset-0 flex items-center justify-center text-white opacity-90 group-hover:opacity-100 transition-opacity">
                    <div class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-md border border-white/40 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 fill-white" viewBox="0 0 24 24"><path d="M8 5v14l11-7z" /></svg>
                    </div>
                </div>
                <div class="absolute top-4 left-4">
                    <span class="bg-primary text-white text-[9px] font-black px-2.5 py-1 rounded-[6px] uppercase shadow-sm">
                        Cho thuê
                    </span>
                </div>
                <div class="absolute bottom-4 left-4 right-4 text-white space-y-1">
                    <h4 class="text-xs font-extrabold line-clamp-2">Căn hộ Studio dịch vụ tách bếp Phú Nhuận</h4>
                    <p class="text-[9px] text-slate-300 font-medium">Phú Nhuận, TPHCM</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION: TIN TỨC BẤT ĐỘNG SẢN (Exact Moso Screenshot 5 News Grid) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8" x-data="{ newsTab: 'report' }">
        <div class="flex justify-between items-end">
            <h2 class="text-2xl font-black text-slate-900">Tin tức bất động sản</h2>
            <a href="#" class="text-xs font-bold text-slate-400 hover:text-primary flex items-center gap-1 transition-colors">
                Xem thêm
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
            </a>
        </div>

        <!-- News Tabs matching screenshot 5 -->
        <div class="flex flex-wrap gap-2 border-b border-slate-100 pb-4">
            <button @click="newsTab = 'report'" class="px-5 py-2 rounded-xl text-xs font-black transition-all" :class="newsTab === 'report' ? 'bg-primary text-white shadow-sm' : 'bg-slate-100 text-slate-500 hover:text-slate-800'">Báo cáo Thị trường BĐS Việt Nam</button>
            <button @click="newsTab = 'view'" class="px-5 py-2 rounded-xl text-xs font-black transition-all" :class="newsTab === 'view' ? 'bg-primary text-white shadow-sm' : 'bg-slate-100 text-slate-500 hover:text-slate-800'">Góc Nhìn NKS</button>
            <button @click="newsTab = 'interior'" class="px-5 py-2 rounded-xl text-xs font-black transition-all" :class="newsTab === 'interior' ? 'bg-primary text-white shadow-sm' : 'bg-slate-100 text-slate-500 hover:text-slate-800'">Nội Thất</button>
            <button @click="newsTab = 'fengshui'" class="px-5 py-2 rounded-xl text-xs font-black transition-all" :class="newsTab === 'fengshui' ? 'bg-primary text-white shadow-sm' : 'bg-slate-100 text-slate-500 hover:text-slate-800'">Phong Thủy</button>
            <button @click="newsTab = 'news'" class="px-5 py-2 rounded-xl text-xs font-black transition-all" :class="newsTab === 'news' ? 'bg-primary text-white shadow-sm' : 'bg-slate-100 text-slate-500 hover:text-slate-800'">Tin Tức</button>
            <button @click="newsTab = 'knowledge'" class="px-5 py-2 rounded-xl text-xs font-black transition-all" :class="newsTab === 'knowledge' ? 'bg-primary text-white shadow-sm' : 'bg-slate-100 text-slate-500 hover:text-slate-800'">Kiến Thức</button>
        </div>

        <!-- News grid -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-8" x-show="newsTab === 'report'" x-transition>
            <!-- Article 1 -->
            <div class="space-y-4 group cursor-pointer">
                <div class="h-44 rounded-2xl overflow-hidden shadow-sm relative border border-slate-100">
                    <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&q=80&w=600" alt="News Image" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-500">
                </div>
                <div class="space-y-1">
                    <h4 class="text-xs font-extrabold text-slate-800 group-hover:text-primary transition-colors leading-snug line-clamp-2">Cách Tối Ưu Hóa Quá Trình Mua Nhà Qua Nền Tảng Online 2026</h4>
                    <p class="text-[10px] text-slate-400 font-medium">7 giờ trước</p>
                </div>
            </div>

            <!-- Article 2 -->
            <div class="space-y-4 group cursor-pointer">
                <div class="h-44 rounded-2xl overflow-hidden shadow-sm relative border border-slate-100">
                    <img src="https://images.unsplash.com/photo-1582407947304-fd86f028f716?auto=format&fit=crop&q=80&w=600" alt="News Image" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-500">
                </div>
                <div class="space-y-1">
                    <h4 class="text-xs font-extrabold text-slate-800 group-hover:text-primary transition-colors leading-snug line-clamp-2">Nhà Đầu Tư Phía Bắc Nam Tiến Thị Trường Bất Động Sản</h4>
                    <p class="text-[10px] text-slate-400 font-medium">8 giờ trước</p>
                </div>
            </div>

            <!-- Article 3 -->
            <div class="space-y-4 group cursor-pointer">
                <div class="h-44 rounded-2xl overflow-hidden shadow-sm relative border border-slate-100">
                    <img src="https://images.unsplash.com/photo-1460317442991-0ec209397118?auto=format&fit=crop&q=80&w=600" alt="News Image" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-500">
                </div>
                <div class="space-y-1">
                    <h4 class="text-xs font-extrabold text-slate-800 group-hover:text-primary transition-colors leading-snug line-clamp-2">Các Yếu Tố Ảnh Hưởng Đến Giá Trị Bất Động Sản Năm 2026</h4>
                    <p class="text-[10px] text-slate-400 font-medium">3 ngày trước</p>
                </div>
            </div>

            <!-- Article 4 -->
            <div class="space-y-4 group cursor-pointer">
                <div class="h-44 rounded-2xl overflow-hidden shadow-sm relative border border-slate-100">
                    <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?auto=format&fit=crop&q=80&w=600" alt="News Image" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-500">
                </div>
                <div class="space-y-1">
                    <h4 class="text-xs font-extrabold text-slate-800 group-hover:text-primary transition-colors leading-snug line-clamp-2">Hướng Dẫn Đăng Tin Nhà Đất Chuẩn SEO và AI Lên Xu hướng NKS</h4>
                    <p class="text-[10px] text-slate-400 font-medium">3 ngày trước</p>
                </div>
            </div>
        </div>

        <div class="text-center py-12 text-slate-400 text-xs font-bold bg-slate-50/50 rounded-3xl" x-show="newsTab !== 'report'" x-transition>
            Chưa có thêm bản tin trong danh mục này. Vui lòng quay lại sau!
        </div>
    </section>

</div>
@endsection
