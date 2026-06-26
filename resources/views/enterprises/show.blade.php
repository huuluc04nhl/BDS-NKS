@extends('layouts.app')

@section('title', $enterprise->name . ' - Thông Tin Doanh Nghiệp & Dự Án | BDS NKS')

@section('content')
<div class="bg-slate-50/50 pb-20" x-data="{ activeTab: 'projects' }">
    
    <!-- Hero / Banner Section with Cover and Logo Overlap -->
    <section class="relative h-[280px] bg-slate-900 overflow-visible">
        <!-- Cover Background Image -->
        <img src="https://images.unsplash.com/photo-1582407947304-fd86f028f716?auto=format&fit=crop&q=80&w=1920" 
             alt="{{ $enterprise->name }} Cover" 
             class="w-full h-full object-cover opacity-30">
        
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/40 to-transparent"></div>

        <!-- Overlap Header info -->
        <div class="absolute -bottom-16 left-0 right-0 z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row items-center sm:items-end gap-5">
                    <!-- Logo container -->
                    <div class="w-32 h-32 rounded-3xl overflow-hidden shadow-2xl border-4 border-white bg-white p-2 shrink-0">
                        <img src="{{ $enterprise->logo ?? 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&q=80&w=200' }}" 
                             alt="{{ $enterprise->name }} Logo" 
                             class="w-full h-full object-cover">
                    </div>
                    <!-- Text Info -->
                    <div class="text-center sm:text-left space-y-2 pb-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-black tracking-widest bg-primary text-white uppercase shadow-sm">
                            Đại diện chính thức
                        </span>
                        <h1 class="text-white font-extrabold text-2xl sm:text-3xl lg:text-4xl tracking-tight font-sans">
                            {{ $enterprise->name }}
                        </h1>
                        <p class="text-slate-300 text-xs sm:text-sm font-semibold flex flex-wrap items-center justify-center sm:justify-start gap-3">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                {{ $enterprise->founded_year ?? '1992' }}
                            </span>
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                {{ $properties->count() }} dự án phân phối
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Empty Spacer for logo overlap -->
    <div class="h-24"></div>

    <!-- Main Content Layout -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Left Column: Business Corporate Info Box -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-premium space-y-6">
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest pb-3 border-b border-slate-50">
                    Thông tin doanh nghiệp
                </h3>

                <!-- Business fact list -->
                <div class="space-y-4">
                    <!-- Representative -->
                    <div class="space-y-1">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Đại diện pháp luật</span>
                        <span class="text-xs font-bold text-slate-700 block">{{ $enterprise->representative ?? 'Đang cập nhật' }}</span>
                    </div>

                    <!-- Tax code -->
                    <div class="space-y-1">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Mã số thuế</span>
                        <span class="text-xs font-extrabold text-slate-800 block">{{ $enterprise->tax_code ?? 'Đang cập nhật' }}</span>
                    </div>

                    <!-- Founded Year -->
                    <div class="space-y-1">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Năm thành lập</span>
                        <span class="text-xs font-bold text-slate-700 block">{{ $enterprise->founded_year ?? 'Đang cập nhật' }}</span>
                    </div>

                    <!-- Phone -->
                    <div class="space-y-1">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Số điện thoại / Hotline</span>
                        <a href="tel:{{ $enterprise->phone }}" class="text-xs font-extrabold text-primary hover:underline block">{{ $enterprise->phone ?? 'Đang cập nhật' }}</a>
                    </div>

                    <!-- Email -->
                    <div class="space-y-1">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Email liên hệ</span>
                        <a href="mailto:{{ $enterprise->email }}" class="text-xs font-bold text-slate-700 hover:text-primary transition-colors block break-all">{{ $enterprise->email ?? 'Đang cập nhật' }}</a>
                    </div>

                    <!-- Website -->
                    <div class="space-y-1">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Website chính thức</span>
                        @if($enterprise->website)
                            <a href="{{ $enterprise->website }}" target="_blank" class="text-xs font-extrabold text-primary hover:underline flex items-center gap-1">
                                {{ str_replace(['http://', 'https://'], '', $enterprise->website) }}
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                            </a>
                        @else
                            <span class="text-xs font-semibold text-slate-400 block">Chưa cập nhật</span>
                        @endif
                    </div>

                    <!-- Address -->
                    <div class="space-y-1 pt-2 border-t border-slate-50">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Địa chỉ trụ sở</span>
                        <span class="text-xs font-medium text-slate-500 leading-relaxed block">{{ $enterprise->address ?? 'Đang cập nhật' }}</span>
                    </div>
                </div>

            </div>

            <!-- Right Column: Tabs (Projects & Introduction) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Navigation Tabs -->
                <div class="flex gap-2 border-b border-slate-100 pb-3">
                    <button @click="activeTab = 'projects'" 
                            :class="activeTab === 'projects' ? 'bg-primary text-white shadow-sm' : 'bg-white text-slate-600 hover:text-slate-900 border border-slate-100'"
                            class="px-6 py-3 rounded-2xl text-xs font-black transition-all">
                        Dự án đang phân phối ({{ $properties->count() }})
                    </button>
                    <button @click="activeTab = 'intro'" 
                            :class="activeTab === 'intro' ? 'bg-primary text-white shadow-sm' : 'bg-white text-slate-600 hover:text-slate-900 border border-slate-100'"
                            class="px-6 py-3 rounded-2xl text-xs font-black transition-all">
                        Hồ sơ & Giới thiệu doanh nghiệp
                    </button>
                </div>

                <!-- Tab content 1: Projects list -->
                <div x-show="activeTab === 'projects'" x-transition>
                    @if($properties->isEmpty())
                        <div class="text-center py-20 bg-white rounded-[32px] border border-slate-100/80 shadow-premium space-y-4">
                            <div class="w-14 h-14 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center mx-auto border border-slate-100">
                                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            </div>
                            <h4 class="font-bold text-slate-700 text-sm">Chưa có dự án nào đăng ký</h4>
                            <p class="text-xs text-slate-400 max-w-xs mx-auto">Doanh nghiệp này chưa đăng tải dự án phân phối chính thức nào trên hệ thống BDS NKS.</p>
                        </div>
                    @else
                        <!-- List grid of properties -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @foreach($properties as $property)
                                <div class="bg-white rounded-[24px] border border-slate-100 shadow-sm card-hover-premium overflow-hidden flex flex-col group p-3 pb-5 relative">
                                    
                                    <!-- Image frame aspect video -->
                                    <div class="h-48 rounded-[18px] overflow-hidden relative">
                                        <img src="{{ $property['featureimg'] }}" 
                                             alt="{{ $property['title'] }}" 
                                             class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-700 ease-out">
                                        
                                        <!-- Floating badges -->
                                        <div class="absolute top-3 left-3 flex gap-1.5 items-center">
                                            <span class="px-2.5 py-1 rounded-[8px] text-[8px] font-black tracking-wider bg-primary text-white uppercase shadow-sm">
                                                {{ $property['transaction_type'] }}
                                            </span>
                                            <span class="text-emerald-600 bg-emerald-50 text-[8px] font-extrabold px-2 py-0.5 rounded-[8px] flex items-center gap-0.5 shadow-sm">
                                                <svg class="w-2.5 h-2.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                                Xác thực
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="px-2 pt-4 flex-grow flex flex-col justify-between space-y-4">
                                        <div class="space-y-2">
                                            <!-- Pricing & Area -->
                                            <div class="flex justify-between items-end">
                                                <span class="text-base font-black text-primary leading-none">
                                                    {{ $property['formatedPrice'] }}
                                                </span>
                                                <span class="text-[10px] font-bold text-slate-400">
                                                    {{ $property['formatedSqrPrice'] ?? (number_format($property['total_area'], 0) . ' m²') }}
                                                </span>
                                            </div>

                                            <!-- Title & Link -->
                                            <a href="{{ route('properties.show', $property['slug']) }}" class="block font-extrabold text-slate-800 hover:text-primary transition-colors text-xs line-clamp-2">
                                                {{ $property['title'] }}
                                            </a>
                                        </div>

                                        <!-- Attributes border-to-border layout -->
                                        <div class="flex items-center justify-between text-slate-500 text-[10px] font-bold pt-3 border-t border-slate-100">
                                            <span class="flex items-center gap-0.5"><svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg> {{ $property['bed'] }} PN</span>
                                            <span class="flex items-center gap-0.5"><svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg> {{ $property['bath'] }} WC</span>
                                            <span class="flex items-center gap-0.5"><svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg> {{ $property['floors'] }} Tầng</span>
                                            <span class="flex items-center gap-0.5"><svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 01.553-.894L9 2l6 3 5.447-2.724A1 1 0 0121 3.168v10.764a1 1 0 01-.553.894L15 18l-6 2z" /></svg> {{ number_format($property['total_area'], 0) }}m²</span>
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Tab content 2: Profile introduction text -->
                <div x-show="activeTab === 'intro'" x-transition style="display: none;">
                    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-premium space-y-6">
                        <h2 class="text-lg font-black text-slate-800">
                            Giới thiệu về {{ $enterprise->name }}
                        </h2>
                        
                        <div class="text-slate-600 text-sm leading-relaxed space-y-4 whitespace-pre-line">
                            {{ $enterprise->description }}
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>
@endsection
