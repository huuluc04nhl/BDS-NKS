@extends('layouts.app')

@section('title', 'Bản Đồ Thuê BDS Chính Chủ TPHCM - NKS')

@section('content')
<!-- Full screen Split View Container -->
<div class="h-[calc(100vh-80px)] h-[calc(100dvh-80px)] flex flex-col md:flex-row overflow-hidden bg-slate-50 relative"
     x-data="propertiesMap()">
     
    <!-- Left Column: Search Filters & Listing Grid -->
    <div class="w-full md:w-[48%] lg:w-[42%] flex flex-col h-full border-r border-slate-100 bg-white z-10 relative"
         :class="mobileView !== 'list' ? 'hidden md:flex' : 'flex'">
        
        <!-- TOP FILTER CARD: Elegant minimalist panel (Moso styled) -->
        <div class="p-4 sm:p-6 border-b border-slate-100 bg-white space-y-4 shadow-sm flex-shrink-0">
            <!-- Keyword search input -->
            <div class="relative bg-slate-50 border border-slate-200/50 rounded-2xl px-4 py-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                <input type="text" x-model="searchKeyword" @input.debounce.300ms="filterProperties()" placeholder="Tìm theo địa chỉ, đường, phường..." class="w-full bg-transparent border-0 p-0 text-slate-800 placeholder-slate-400 font-bold focus:outline-none focus:ring-0 text-xs">
            </div>

            <!-- Filters Grid -->
            <div class="grid grid-cols-2 gap-3">
                <!-- Type Select -->
                <div>
                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1">Loại hình</label>
                    <select x-model="selectedType" @change="filterProperties()" class="w-full bg-slate-50 border border-slate-200/50 rounded-xl px-3 py-2 text-xs font-bold text-slate-700 focus:outline-none focus:border-primary">
                        <option value="">Tất cả loại hình</option>
                        <template x-for="type in [...new Set(properties.map(p => p.rstype))].filter(Boolean)">
                            <option :value="type" x-text="type"></option>
                        </template>
                    </select>
                </div>

                <!-- Price limit select -->
                <div>
                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1 flex justify-between">
                        <span>Giá tối đa</span>
                        <span class="text-primary font-black" x-text="priceMax >= 100 ? 'Vô hạn' : priceMax + ' triệu'"></span>
                    </label>
                    <input type="range" min="5" max="100" step="5" x-model="priceMax" @input="filterProperties()" class="w-full h-1.5 bg-slate-100 rounded-lg appearance-none cursor-pointer accent-primary mt-2">
                </div>
            </div>

            <!-- Advanced filter row: Bed, Bath, Reset button (Flex-wrap prevents mobile overflow!) -->
            <div class="flex flex-wrap items-center gap-2 pt-1">
                <select x-model="bedsCount" @change="filterProperties()" class="bg-slate-50 border border-slate-200/50 rounded-xl px-2.5 py-1.5 text-[10px] font-bold text-slate-500 focus:outline-none">
                    <option value="">Số phòng ngủ</option>
                    <option value="1">1 PN</option>
                    <option value="2">2 PN</option>
                    <option value="3">3+ PN</option>
                </select>
                
                <select x-model="bathsCount" @change="filterProperties()" class="bg-slate-50 border border-slate-200/50 rounded-xl px-2.5 py-1.5 text-[10px] font-bold text-slate-500 focus:outline-none">
                    <option value="">Số WC</option>
                    <option value="1">1 WC</option>
                    <option value="2">2 WC</option>
                    <option value="3">3+ WC</option>
                </select>

                <button @click="resetFilters()" class="text-[10px] font-bold text-slate-400 hover:text-primary transition-colors ml-auto py-1">
                    Đặt lại lọc
                </button>
            </div>
        </div>

        <!-- LISTING SECTION: Scrollable List of Premium Property Cards -->
        <div class="flex-grow overflow-y-auto px-4 sm:px-6 py-6 space-y-6">
            <!-- Properties Count Header -->
            <div class="flex justify-between items-center text-xs text-slate-400 font-semibold mb-2">
                <span>Tìm thấy <span class="text-slate-800 font-extrabold" x-text="filteredProperties.length">0</span> bất động sản</span>
                <span>TP. Hồ Chí Minh</span>
            </div>

            <!-- Staggered properties cards -->
            <template x-if="filteredProperties.length === 0">
                <div class="text-center py-20 space-y-4">
                    <div class="w-14 h-14 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center mx-auto border border-slate-100">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </div>
                    <h4 class="font-bold text-slate-700 text-sm">Không tìm thấy kết quả phù hợp</h4>
                    <p class="text-xs text-slate-400 max-w-xs mx-auto">Vui lòng điều chỉnh hoặc mở rộng bộ lọc giá, diện tích để tìm được căn hộ vừa ý.</p>
                </div>
            </template>

            <div class="space-y-6">
                <template x-for="p in filteredProperties" :key="p.id">
                    <div :id="'property-card-' + p.id"
                         @click="focusProperty(p)"
                         class="bg-white rounded-[24px] border border-slate-100 shadow-sm card-hover-premium overflow-hidden flex flex-col sm:flex-row group p-3 cursor-pointer"
                         :class="activePropertyId === p.id && 'border-primary shadow-premium ring-2 ring-primary/5'">
                        
                        <!-- Image Container with Verify Badge -->
                        <div @click.stop="openModal(p)" class="w-full sm:w-44 h-40 rounded-[18px] overflow-hidden relative flex-shrink-0 cursor-pointer">
                            <img :src="p.featureimg" :alt="p.title" class="w-full h-full object-cover group-hover:scale-103 transition-transform duration-700 ease-out">
                            <div class="absolute top-2.5 left-2.5 flex flex-col gap-1.5">
                                <span class="inline-flex items-center gap-0.5 px-2 py-1 rounded-[6px] text-[8px] font-black tracking-widest bg-emerald-500 text-white uppercase shadow-sm">
                                    <svg class="w-2.5 h-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    3 Thật
                                </span>
                            </div>
                            <!-- Share Button -->
                            <button @click.stop="openShare('{{ url('/properties') }}/' + p.slug, p.title)" 
                                    class="absolute top-2.5 right-11.5 w-7.5 h-7.5 rounded-full bg-white/90 text-slate-600 hover:bg-white hover:text-primary shadow-md flex items-center justify-center transition-all duration-200 active:scale-95">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 10.742l4.622-2.311m0 0a3 3 0 10-2.667-1.772a3 3 0 002.667 1.772zm0 6.518l-4.623-2.311a3 3 0 11-2.667-1.772a3 3 0 012.667 1.772zm1.144 0a3 3 0 112.667 1.772a3 3 0 01-2.667-1.772z" />
                                </svg>
                            </button>
                            <button @click.stop="toggleFav(p)" 
                                    class="absolute top-2.5 right-2.5 w-7.5 h-7.5 rounded-full flex items-center justify-center shadow-md transition-all duration-200"
                                    :class="isFav(p.id) ? 'bg-red-500 text-white animate-heart-pop' : 'bg-white/90 text-slate-600 hover:bg-white hover:text-red-500'">
                                <svg class="w-3.5 h-3.5" :fill="isFav(p.id) ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                            </button>
                        </div>

                        <!-- Content Section -->
                        <div class="p-3 sm:pl-5 sm:py-2 flex-grow flex flex-col justify-between">
                            <div class="space-y-1.5">
                                <!-- Tags row -->
                                <div class="flex gap-2">
                                    <span class="bg-slate-100 text-slate-600 text-[9px] font-bold px-2 py-0.5 rounded-[6px]" x-text="p.rstype">Căn hộ</span>
                                    <span class="text-emerald-600 bg-emerald-50 text-[9px] font-extrabold px-2 py-0.5 rounded-[6px] flex items-center gap-0.5">
                                        <svg class="w-2.5 h-2.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                        Xác thực
                                    </span>
                                </div>

                                <!-- Pricing -->
                                <div class="flex justify-between items-end">
                                    <span class="text-base font-black text-primary leading-none" x-text="p.formatedPrice"></span>
                                    <span class="text-[10px] font-bold text-slate-400" x-text="p.formatedSqrPrice || (p.total_area + ' m²')"></span>
                                </div>

                                <!-- Title -->
                                <h3 @click.stop="openModal(p)" class="font-extrabold text-slate-800 text-xs leading-snug line-clamp-2 hover:text-primary transition-colors duration-300 cursor-pointer" x-text="p.title"></h3>

                                <!-- Sleek trigger button for the Modal -->
                                <div class="pt-1">
                                    <button @click.stop="openModal(p)" class="text-[10px] font-black text-primary hover:underline uppercase tracking-wider flex items-center gap-0.5">
                                        Xem chi tiết
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Attributes Horizontal Row -->
                            <div class="flex items-center justify-between text-slate-400 text-[10px] font-bold pt-2 border-t border-slate-100 mt-2">
                                <span class="flex items-center gap-0.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg> <span x-text="p.bed || 1"></span> PN</span>
                                <span class="flex items-center gap-0.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg> <span x-text="p.bath || 1"></span> WC</span>
                                <span class="flex items-center gap-0.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg> <span x-text="p.floors || 1"></span> Tầng</span>
                                <span class="flex items-center gap-0.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 01.553-.894L9 2l6 3 5.447-2.724A1 1 0 0121 3.168v10.764a1 1 0 01-.553.894L15 18l-6 2z" /></svg> <span x-text="number_format(p.total_area || 0, 0)"></span> m²</span>
                            </div>
                        </div>

                    </div>
                </template>
            </div>
        </div>

    </div>

    <!-- Right Column: Full-Screen MapLibre GL Vector Map -->
    <div class="flex-grow h-full bg-slate-100 z-0 relative"
         :class="mobileView !== 'map' ? 'hidden md:block' : 'block'">
        <div id="maplibre-container" class="w-full h-full"></div>
    </div>

    <!-- FLOATING MOBILE SWITCHER BUTTON (Identical to Airbnb / Premium UX patterns!) -->
    <button @click="mobileView = (mobileView === 'list' ? 'map' : 'list'); if (mobileView === 'map') { $nextTick(() => { map.resize(); }) }" 
            class="md:hidden fixed bottom-6 left-1/2 transform -translate-x-1/2 z-50 bg-slate-900 text-white font-extrabold px-6 py-3.5 rounded-full shadow-2xl flex items-center gap-2 text-xs uppercase tracking-wide hover:scale-105 active:scale-95 transition-custom-all border border-white/10">
        <!-- Map Icon (visible when list is shown) -->
        <span class="flex items-center gap-1.5" x-show="mobileView === 'list'">
            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 01.553-.894L9 2l6 3 5.447-2.724A1 1 0 0121 3.168v10.764a1 1 0 01-.553.894L15 18l-6 2z" /></svg>
            Xem Bản đồ
        </span>
        
        <!-- List Bullet Icon (visible when map is shown) -->
        <span class="flex items-center gap-1.5" x-show="mobileView === 'map'" style="display: none;">
            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
            Xem Danh sách
        </span>
    </button>

    <!-- DETAIL MODAL OVERLAY (Sleek Blur Backdrop & Premium Glassmorphic Container) -->
    <div x-show="showDetailModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         style="display: none;"
         x-cloak>
        
        <!-- Modal Card Container -->
        <div @click.away="closeModal()" 
             x-show="showDetailModal"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="bg-white rounded-[32px] shadow-2xl max-w-5xl w-full max-h-[90vh] overflow-hidden border border-slate-100 flex flex-col relative">
            
            <!-- Close button floating -->
            <button @click="closeModal()" class="absolute top-4 right-4 z-50 w-9 h-9 rounded-full bg-white/90 hover:bg-white text-slate-500 hover:text-slate-800 shadow-md flex items-center justify-center transition-all duration-200 active:scale-95 border border-slate-100">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>

            <!-- Scrollable Body content -->
            <div class="overflow-y-auto flex-grow" x-show="selectedProperty" x-data="{
                nextSlide() { modalActiveSlide = (modalActiveSlide + 1) % (selectedProperty.gallery?.length || 1) },
                prevSlide() { modalActiveSlide = (modalActiveSlide - 1 + (selectedProperty.gallery?.length || 1)) % (selectedProperty.gallery?.length || 1) }
            }">
                <div class="grid grid-cols-1 lg:grid-cols-12">
                    
                    <!-- Left Section: Gallery, Specs, Description (lg:col-span-7) -->
                    <div class="lg:col-span-7 p-6 sm:p-8 space-y-6 border-r border-slate-100">
                        
                        <!-- Alpine Slider inside Modal -->
                        <div class="relative rounded-3xl overflow-hidden bg-slate-50 h-64 sm:h-80 border border-slate-100">
                            <!-- Slides -->
                            <template x-for="(slide, index) in (selectedProperty?.gallery || [])" :key="index">
                                <div x-show="modalActiveSlide === index" 
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100"
                                     class="absolute inset-0 w-full h-full">
                                    <img :src="slide" alt="Property Image" class="w-full h-full object-cover">
                                </div>
                            </template>

                            <div class="absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-black/40 to-transparent pointer-events-none"></div>

                            <!-- Next/Prev Buttons -->
                            <template x-if="(selectedProperty?.gallery || []).length > 1">
                                <div class="absolute inset-0 flex items-center justify-between px-4 pointer-events-none">
                                    <button @click.stop="prevSlide()" class="w-8 h-8 rounded-full bg-white/90 text-slate-800 hover:bg-white flex items-center justify-center shadow-md transition-all pointer-events-auto active:scale-90">
                                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                                    </button>
                                    <button @click.stop="nextSlide()" class="w-8 h-8 rounded-full bg-white/90 text-slate-800 hover:bg-white flex items-center justify-center shadow-md transition-all pointer-events-auto active:scale-90">
                                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                    </button>
                                </div>
                            </template>

                            <!-- Slider Dots -->
                            <template x-if="(selectedProperty?.gallery || []).length > 1">
                                <div class="absolute bottom-3 left-1/2 transform -translate-x-1/2 flex gap-1.5">
                                    <template x-for="(slide, index) in (selectedProperty?.gallery || [])" :key="index">
                                        <button @click.stop="modalActiveSlide = index" 
                                                class="h-1 rounded-full transition-all duration-300"
                                                :class="modalActiveSlide === index ? 'w-4 bg-primary' : 'w-1 bg-white/60'"></button>
                                    </template>
                                </div>
                            </template>

                            <!-- Counter -->
                            <div class="absolute top-3 right-3 bg-black/65 text-white text-[9px] font-black px-2.5 py-1 rounded-full flex items-center gap-1">
                                <span x-text="(modalActiveSlide + 1) + '/' + (selectedProperty?.gallery?.length || 1)"></span> Ảnh
                            </div>
                        </div>

                        <!-- Title, badges, address -->
                        <div class="space-y-3">
                            <div class="flex flex-wrap gap-1.5 items-center">
                                <span class="px-2.5 py-1 rounded-md text-[8px] font-black tracking-widest bg-emerald-500 text-white uppercase shadow-sm flex items-center gap-0.5">
                                    <svg class="w-2.5 h-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    Xác thực 3 Thật
                                </span>
                                <span class="bg-slate-100 text-slate-600 text-[8px] font-extrabold px-2.5 py-1 rounded-md uppercase" x-text="selectedProperty?.rstype"></span>
                                <span class="text-[9px] text-slate-400 font-bold ml-auto" x-text="'ID: ' + selectedProperty?.id"></span>
                            </div>
                            <h2 class="text-xl sm:text-2xl font-black text-slate-900 leading-snug" x-text="selectedProperty?.title"></h2>
                            <p class="text-xs font-semibold text-slate-400 flex items-center gap-1" x-text="selectedProperty?.address"></p>
                        </div>

                        <!-- Pricing & Key specs grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 py-4 border-t border-b border-slate-100 bg-slate-50/50 p-4 rounded-2xl">
                            <div class="flex flex-col">
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Giá chính chủ</span>
                                <span class="text-base font-black text-primary leading-none" x-text="selectedProperty?.formatedPrice"></span>
                            </div>
                            <div class="flex flex-col border-l border-slate-200/60 pl-4">
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Diện tích</span>
                                <span class="text-base font-black text-slate-800 leading-none" x-text="(selectedProperty?.total_area || 45) + 'm²'"></span>
                            </div>
                            <div class="flex flex-col border-l border-slate-200/60 pl-4">
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Phòng ngủ</span>
                                <span class="text-base font-black text-slate-800 leading-none" x-text="(selectedProperty?.bed || 1) + ' PN'"></span>
                            </div>
                            <div class="flex flex-col border-l border-slate-200/60 pl-4">
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Phòng tắm</span>
                                <span class="text-base font-black text-slate-800 leading-none" x-text="(selectedProperty?.bath || 1) + ' WC'"></span>
                            </div>
                        </div>

                        <!-- Description description -->
                        <div class="space-y-2.5">
                            <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">Mô tả bất động sản</h4>
                            <div class="text-xs text-slate-500 leading-relaxed font-medium space-y-2">
                                <p>Căn hộ cao cấp sang trọng thiết kế cực kỳ hiện đại với nội thất nhập khẩu cao cấp. Vị trí vô cùng đắc địa ngay trong trung tâm thành phố, thuận tiện giao thông đi lại.</p>
                                <p>Đầy đủ các tiện ích dân sinh vượt trội xung quanh: trường học, siêu thị, bệnh viện lớn. Thích hợp cho hộ gia đình định cư lâu dài hoặc người đi làm văn phòng quận trung tâm.</p>
                            </div>
                        </div>

                        <!-- DETAILED MAPLIBRE GPS MAP -->
                        <div class="space-y-4 pt-4 border-t border-slate-100">
                            <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">Vị trí địa lý chính xác (3 Thật)</h4>
                            <div class="h-[220px] rounded-2xl overflow-hidden border border-slate-100 shadow-sm relative">
                                <div id="modal-property-map" class="w-full h-full"></div>
                            </div>
                        </div>

                    </div>

                    <!-- Right Section: Host & Booking Form (lg:col-span-5) -->
                    <div class="lg:col-span-5 p-6 sm:p-8 bg-slate-50/40 relative">
                        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-6 lg:sticky lg:top-6 h-fit">
                            
                            <!-- Contact Host info -->
                            <div class="space-y-4">
                                <div class="flex justify-between items-center pb-2.5 border-b border-slate-100">
                                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider">Thông tin liên hệ</h3>
                                    <div class="flex gap-2">
                                        <!-- Share Button -->
                                        <button @click.stop="openShare('{{ url('/properties') }}/' + selectedProperty.slug, selectedProperty.title)" 
                                                class="w-7.5 h-7.5 rounded-full bg-white text-slate-500 hover:bg-slate-100 hover:text-primary flex items-center justify-center shadow-xs border border-slate-200/40 transition-all duration-200 active:scale-95">
                                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 10.742l4.622-2.311m0 0a3 3 0 10-2.667-1.772a3 3 0 002.667 1.772zm0 6.518l-4.623-2.311a3 3 0 11-2.667-1.772a3 3 0 012.667 1.772zm1.144 0a3 3 0 112.667 1.772a3 3 0 01-2.667-1.772z" />
                                            </svg>
                                        </button>
                                        <!-- Favorite Button -->
                                        <button @click.stop="toggleFav(selectedProperty)" 
                                                class="w-7.5 h-7.5 rounded-full flex items-center justify-center shadow-xs border border-slate-200/40 transition-all duration-200 active:scale-95"
                                                :class="isFav(selectedProperty.id) ? 'bg-red-500 text-white border-transparent animate-heart-pop' : 'bg-white text-slate-500 hover:bg-slate-100 hover:text-red-500'">
                                            <svg class="w-4.5 h-4.5" :fill="isFav(selectedProperty.id) ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Host details -->
                                <div class="flex items-center gap-3.5 bg-slate-50/70 p-3 rounded-2xl border border-slate-100">
                                    <div class="w-11 h-11 rounded-full border border-slate-100 overflow-hidden bg-white flex-shrink-0">
                                        <img :src="selectedProperty?.sale?.avatar" alt="Host Avatar" class="w-full h-full object-cover">
                                    </div>
                                    <div class="overflow-hidden">
                                        <h4 class="font-extrabold text-xs text-slate-800 truncate" x-text="selectedProperty?.sale?.name"></h4>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wide">Chủ nhà chính chủ</p>
                                    </div>
                                </div>

                                <!-- Phone & Zalo CTAs -->
                                <div class="grid grid-cols-2 gap-3">
                                    <a :href="'tel:' + selectedProperty?.sale?.phone" class="bg-primary text-white font-extrabold text-xs py-3 rounded-2xl shadow-sm btn-hover-premium text-center flex items-center justify-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                        Gọi điện
                                    </a>
                                    <a :href="'https://zalo.me/' + selectedProperty?.sale?.phone" target="_blank" class="bg-white border border-slate-200 text-primary font-extrabold text-xs py-3 rounded-2xl shadow-xs btn-hover-premium text-center flex items-center justify-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                                        Nhắn Zalo
                                    </a>
                                </div>
                            </div>

                            <hr class="border-slate-100">

                            <!-- Scheduler Form -->
                            <div class="space-y-4">
                                <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">Đặt lịch hẹn xem nhà</h4>
                                
                                <!-- Success Message Alert -->
                                <div x-show="isApptSuccess" class="bg-green-50 border border-green-200 text-green-800 p-4 rounded-2xl text-xs text-center space-y-1">
                                    <p class="font-bold">Đặt lịch xem thành công!</p>
                                    <p class="text-slate-500">Đang chuyển hướng đến danh sách lịch hẹn...</p>
                                </div>

                                <form @submit.prevent="bookAppointment()" x-show="!isApptSuccess" class="space-y-3.5">
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-[8px] font-black text-slate-400 uppercase tracking-wider mb-1">Ngày hẹn</label>
                                            <input type="date" :min="todayDate" x-model="apptDate" required class="w-full bg-slate-50 border border-slate-200/50 rounded-xl px-3 py-2 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700">
                                        </div>
                                        <div>
                                            <label class="block text-[8px] font-black text-slate-400 uppercase tracking-wider mb-1">Giờ hẹn</label>
                                            <input type="time" x-model="apptTime" required class="w-full bg-slate-50 border border-slate-200/50 rounded-xl px-3 py-2 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700">
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <input type="text" x-model="apptName" placeholder="Họ và tên của bạn" required class="w-full bg-slate-50 border border-slate-200/50 rounded-xl px-3 py-2.5 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-all">
                                        <input type="tel" x-model="apptPhone" placeholder="Số điện thoại của bạn" required class="w-full bg-slate-50 border border-slate-200/50 rounded-xl px-3 py-2.5 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-all">
                                    </div>
                                    <button type="submit" class="w-full bg-primary text-white font-extrabold py-3.5 rounded-2xl text-xs shadow-md btn-hover-premium">
                                        Gửi yêu cầu đặt lịch hẹn
                                    </button>
                                </form>
                            </div>

                        </div>

                    </div>

                </div>
            </div>

        </div>

    </div>

</div>

<!-- Pass property list as JSON safely to the page client-side namespace -->
<script>
    window.NKS_PROPERTIES = @json($properties);
</script>

<!-- Helper function for formatting in inline template -->
<script>
    function number_format(number, decimals) {
        return parseFloat(number).toFixed(decimals);
    }
</script>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('propertiesMap', () => ({
            properties: [],
            filteredProperties: [],
            favorites: [],
            
            // Filters State
            searchKeyword: '',
            selectedType: '',
            priceMax: 100,
            bedsCount: '',
            bathsCount: '',
            
            mobileView: 'list',
            map: null,
            markers: [],
            activePropertyId: null,
            showDetailModal: false,
            selectedProperty: null,
            modalActiveSlide: 0,
            modalMap: null,
            apptDate: '',
            apptTime: '',
            apptName: '',
            apptPhone: '',
            isApptSuccess: false,
            todayDate: '',

            init() {
                const today = new Date();
                const yyyy = today.getFullYear();
                const mm = String(today.getMonth() + 1).padStart(2, '0');
                const dd = String(today.getDate()).padStart(2, '0');
                this.todayDate = `${yyyy}-${mm}-${dd}`;

                this.properties = window.NKS_PROPERTIES || [];
                this.filteredProperties = [...this.properties];
                
                const urlParams = new URLSearchParams(window.location.search);
                this.searchKeyword = urlParams.get('kw') || '';
                this.selectedType = urlParams.get('rstype') || '';
                const priceParam = urlParams.get('price_max');
                if (priceParam) this.priceMax = parseInt(priceParam);
                
                const savedFavs = localStorage.getItem('nks_favorites');
                if (savedFavs) {
                    this.favorites = JSON.parse(savedFavs);
                }
                window.addEventListener('nks-fav-change', () => {
                    const current = localStorage.getItem('nks_favorites');
                    this.favorites = current ? JSON.parse(current) : [];
                });
                
                this.filterProperties();
                
                this.$nextTick(() => {
                    this.initMap();
                    
                    const focusParam = urlParams.get('focus');
                    if (focusParam) {
                        setTimeout(() => {
                            const p = this.properties.find(item => String(item.id) === String(focusParam));
                            if (p) {
                                this.focusProperty(p);
                                // Scroll card into view
                                const cardEl = document.getElementById('property-card-' + p.id);
                                if (cardEl) {
                                    cardEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                                }
                            }
                        }, 800);
                    }
                });

                window.openPropertyModal = (id) => {
                    const p = this.properties.find(item => item.id === id);
                    if (p) {
                        this.openModal(p);
                    }
                };
            },

            openModal(p) {
                this.selectedProperty = p;
                this.showDetailModal = true;
                this.modalActiveSlide = 0;
                this.apptDate = '';
                this.apptTime = '';
                this.isApptSuccess = false;
                
                const savedUser = localStorage.getItem('nks_user');
                if (savedUser) {
                    const u = JSON.parse(savedUser);
                    this.apptName = u.name || '';
                    this.apptPhone = u.phone || '';
                } else {
                    this.apptName = '';
                    this.apptPhone = '';
                }
                
                this.$nextTick(() => {
                    this.initModalMap(p);
                });
            },
            
            closeModal() {
                this.showDetailModal = false;
                this.selectedProperty = null;
                if (this.modalMap) {
                    this.modalMap.remove();
                    this.modalMap = null;
                }
            },
            
            initModalMap(p) {
                const geoString = p.geolocation;
                if (!geoString) return;
                
                const [lat, lng] = geoString.split(',').map(parseFloat);
                if (isNaN(lat) || isNaN(lng)) return;
                
                if (this.modalMap) {
                    this.modalMap.remove();
                    this.modalMap = null;
                }
                
                this.modalMap = new maplibregl.Map({
                    container: 'modal-property-map',
                    style: {
                        version: 8,
                        sources: {
                            'osm-tiles': {
                                type: 'raster',
                                tiles: [
                                    'https://a.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}.png'
                                ],
                                tileSize: 256,
                                attribution: '&copy; CartoDB &copy; OpenStreetMap contributors'
                            }
                        },
                        layers: [
                            {
                                id: 'osm-layer',
                                type: 'raster',
                                source: 'osm-tiles',
                                minzoom: 0,
                                maxzoom: 19
                            }
                        ]
                    },
                    center: [lng, lat],
                    zoom: 15
                });
                
                this.modalMap.addControl(new maplibregl.NavigationControl(), 'top-right');
                
                new maplibregl.Marker()
                    .setLngLat([lng, lat])
                    .addTo(this.modalMap);
            },
            
            async bookAppointment() {
                if (!this.apptDate || !this.apptTime || !this.apptName || !this.apptPhone) {
                    alert('Vui lòng điền đầy đủ thông tin đặt lịch hẹn.');
                    return;
                }

                let userId = null;
                const savedUser = localStorage.getItem('nks_user');
                if (savedUser) {
                    const u = JSON.parse(savedUser);
                    userId = u.id;
                }

                try {
                    const res = await fetch('/nks-api/appointments/book', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            user_id: userId,
                            property_id: String(this.selectedProperty.id),
                            appt_name: this.apptName,
                            appt_phone: this.apptPhone,
                            appointment_date: this.apptDate,
                            appointment_time: this.apptTime
                        })
                    });

                    if (res.ok) {
                        const data = await res.json();
                        const saved = localStorage.getItem('nks_appointments');
                        const currentAppts = saved ? JSON.parse(saved) : [];
                        
                        const newAppt = {
                            id: data.appointment.id,
                            property_title: this.selectedProperty.title,
                            property_slug: this.selectedProperty.slug,
                            date: this.apptDate,
                            time: this.apptTime,
                            name: this.apptName,
                            phone: this.apptPhone,
                            status: 'confirmed',
                            host_name: this.selectedProperty.sale?.name || 'Anh Minh',
                            host_phone: this.selectedProperty.sale?.phone || '0932030958'
                        };
                        
                        currentAppts.push(newAppt);
                        localStorage.setItem('nks_appointments', JSON.stringify(currentAppts));
                        
                        this.isApptSuccess = true;
                        this.apptDate = '';
                        this.apptTime = '';
                        
                        setTimeout(() => {
                            this.isApptSuccess = false;
                            this.closeModal();
                            window.location.href = '/profile?tab=appointments';
                        }, 2000);
                    } else {
                        alert('Đặt lịch hẹn xem nhà không thành công.');
                    }
                } catch (e) {
                    alert('Lỗi kết nối máy chủ CSDL.');
                }
            },
            
            filterProperties() {
                this.filteredProperties = this.properties.filter(p => {
                    const matchesKeyword = !this.searchKeyword || 
                        (p.title && p.title.toLowerCase().includes(this.searchKeyword.toLowerCase())) ||
                        (p.address && p.address.toLowerCase().includes(this.searchKeyword.toLowerCase()));
                        
                    const matchesType = !this.selectedType || p.rstype === this.selectedType;
                    
                    const priceInMillions = (p.price || p.rentprice || 0) / 1000000;
                    const matchesPrice = parseInt(this.priceMax) >= 100 || priceInMillions <= parseInt(this.priceMax);
                    
                    const matchesBeds = !this.bedsCount || parseInt(p.bed || 0) === parseInt(this.bedsCount);
                    
                    const matchesBaths = !this.bathsCount || parseInt(p.bath || 0) === parseInt(this.bathsCount);
                    
                    return matchesKeyword && matchesType && matchesPrice && matchesBeds && matchesBaths;
                });
                
                this.updateMapMarkers();
            },
            
            resetFilters() {
                this.searchKeyword = '';
                this.selectedType = '';
                this.priceMax = 100;
                this.bedsCount = '';
                this.bathsCount = '';
                this.filterProperties();
            },
            
            isFav(id) {
                return this.favorites.some(f => f.id === id);
            },
            
            async toggleFav(property) {
                let userId = null;
                const savedUser = localStorage.getItem('nks_user');
                if (savedUser) {
                    const u = JSON.parse(savedUser);
                    userId = u.id;
                }

                if (userId) {
                    try {
                        const res = await fetch('/nks-api/favorites/toggle', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                user_id: userId,
                                property_id: property.id > 100 ? property.id : null,
                                external_property_id: property.id <= 100 ? String(property.id) : null
                            })
                        });
                        
                        if (!res.ok) {
                            const err = await res.json();
                            alert(err.message || 'Lỗi khi lưu tin yêu thích.');
                            return;
                        }
                    } catch (e) {
                        alert('Lỗi kết nối máy chủ CSDL.');
                        return;
                    }
                } else {
                    alert('Vui lòng đăng nhập để lưu tin yêu thích.');
                    window.location.href = '/profile?tab=login';
                    return;
                }

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
            },
            
            initMap() {
                const center = [106.6710, 10.7932]; 
                
                this.map = new maplibregl.Map({
                    container: 'maplibre-container',
                    style: {
                        version: 8,
                        sources: {
                            'osm-tiles': {
                                type: 'raster',
                                tiles: [
                                    'https://a.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}.png'
                                ],
                                tileSize: 256,
                                attribution: '&copy; CartoDB &copy; OpenStreetMap contributors'
                            }
                        },
                        layers: [
                            {
                                id: 'osm-layer',
                                type: 'raster',
                                source: 'osm-tiles',
                                minzoom: 0,
                                maxzoom: 19
                            }
                        ]
                    },
                    center: center,
                    zoom: 13
                });
                
                this.map.addControl(new maplibregl.NavigationControl(), 'top-right');
                
                this.map.on('load', () => {
                    this.updateMapMarkers();
                });
            },
            
            updateMapMarkers() {
                if (!this.map) return;
                
                this.markers.forEach(m => m.remove());
                this.markers = [];
                
                if (this.filteredProperties.length === 0) return;
                
                const bounds = new maplibregl.LngLatBounds();
                let validCoords = 0;
                
                this.filteredProperties.forEach(p => {
                    if (!p.geolocation) return;
                    
                    const [lat, lng] = p.geolocation.split(',').map(parseFloat);
                    if (isNaN(lat) || isNaN(lng)) return;
                    
                    bounds.extend([lng, lat]);
                    validCoords++;
                    
                    const el = document.createElement('div');
                    el.className = 'custom-map-marker';
                    el.style.cursor = 'pointer';
                    
                    const priceNum = (p.price || p.rentprice || 0) / 1000000;
                    const priceBadge = priceNum >= 1000 ? (priceNum/1000).toFixed(1) + ' tỷ' : priceNum.toFixed(0) + ' tr';
                    
                    el.innerHTML = `
                        <div class='bg-white text-primary border-2 border-primary hover:bg-primary hover:text-white font-extrabold text-[10px] px-2.5 py-1 rounded-full shadow-lg transition-all transform hover:scale-105 flex items-center gap-0.5 whitespace-nowrap'>
                            <svg class='w-3 h-3 text-emerald-500' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z' clip-rule='evenodd'></path></svg>
                            ${priceBadge}
                        </div>
                    `;
                    
                    const popupContent = `
                        <div class='w-56 overflow-hidden flex flex-col font-sans'>
                            <div class='h-28 overflow-hidden relative'>
                                <img src='${p.featureimg}' class='w-full h-full object-cover' />
                                <span class='absolute top-2 left-2 px-2 py-0.5 rounded-md text-[8px] font-black bg-primary text-white uppercase'>${p.rstype}</span>
                            </div>
                            <div class='p-3 space-y-2 bg-white'>
                                <h4 class='font-extrabold text-xs text-slate-800 line-clamp-2 hover:text-primary transition-colors'>
                                    <a href='javascript:void(0)' onclick='window.openPropertyModal(${p.id})'>${p.title}</a>
                                </h4>
                                <p class='text-[10px] text-slate-400 truncate'>${p.address}</p>
                                <div class='flex justify-between items-center pt-1 border-t border-slate-50'>
                                    <span class='text-xs font-black text-primary'>${p.formatedPrice}</span>
                                    <a href='javascript:void(0)' onclick='window.openPropertyModal(${p.id})' class='text-[9px] font-bold text-slate-400 hover:text-primary transition-colors'>Chi tiết &rarr;</a>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    const popup = new maplibregl.Popup({ offset: 12 }).setHTML(popupContent);
                    
                    const marker = new maplibregl.Marker({ element: el })
                        .setLngLat([lng, lat])
                        .setPopup(popup)
                        .addTo(this.map);
                        
                    el.addEventListener('click', () => {
                        this.activePropertyId = p.id;
                        const cardEl = document.getElementById('property-card-' + p.id);
                        if (cardEl) {
                            cardEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        }
                    });
                    
                    this.markers.push(marker);
                });
                
                if (validCoords > 0) {
                    this.map.fitBounds(bounds, {
                        padding: 60,
                        maxZoom: 15
                    });
                }
            },
            
            focusProperty(p) {
                this.activePropertyId = p.id;
                if (!this.map || !p.geolocation) return;
                
                const [lat, lng] = p.geolocation.split(',').map(parseFloat);
                if (isNaN(lat) || isNaN(lng)) return;
                
                if (window.innerWidth < 768) {
                    this.mobileView = 'map';
                    this.$nextTick(() => {
                        this.map.resize();
                        this.performFlyTo(lng, lat, p);
                    });
                } else {
                    this.performFlyTo(lng, lat, p);
                }
            },
            
            performFlyTo(lng, lat, p) {
                this.map.flyTo({
                    center: [lng, lat],
                    zoom: 14.5,
                    speed: 1.2
                });
                
                const index = this.filteredProperties.findIndex(item => item.id === p.id);
                if (index > -1 && this.markers[index]) {
                    this.markers[index].togglePopup();
                }
            }
        }));
    });
</script>
@endsection
