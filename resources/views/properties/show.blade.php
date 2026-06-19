@extends('layouts.app')

@section('title', $property['title'] . ' - BDS NKS')

@section('content')
<div class="py-12 bg-slate-50 min-h-screen" x-data="propertyDetail(@js($property))">
     
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        
        <!-- BACK NAVIGATION -->
        <div class="flex items-center justify-between text-xs font-bold text-slate-400">
            <a href="/properties" class="flex items-center gap-1 hover:text-primary transition-colors">
                &larr; Quay lại bản đồ thuê
            </a>
            <span>Trang chủ / Chi tiết BDS / {{ $property['rstype'] }}</span>
        </div>

        <!-- IMAGE GALLERY CAROUSEL (Slider ảnh mượt mà bằng AlpineJS) -->
        <div class="relative rounded-[36px] overflow-hidden shadow-premium border border-slate-100 bg-white p-2 h-[300px] md:h-[450px]"
             x-data="{
                 activeSlide: 0,
                 slides: @js($property['gallery'] ?? [$property['featureimg']]),
                 next() { this.activeSlide = (this.activeSlide + 1) % this.slides.length },
                 prev() { this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length }
             }">
            <!-- Main Slides View -->
            <div class="w-full h-full relative rounded-[28px] overflow-hidden">
                <template x-for="(slide, index) in slides" :key="index">
                    <div x-show="activeSlide === index" 
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 scale-98"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute inset-0 w-full h-full">
                        <img :src="slide" alt="Property Image" class="w-full h-full object-cover">
                    </div>
                </template>

                <!-- Gradient Overlay -->
                <div class="absolute inset-x-0 bottom-0 h-28 bg-gradient-to-t from-black/40 to-transparent pointer-events-none"></div>

                <!-- Slider Controls (Left/Right Arrows) -->
                <template x-if="slides.length > 1">
                    <div class="absolute inset-0 flex items-center justify-between px-4 sm:px-6 pointer-events-none">
                        <button @click="prev()" class="w-9 h-9 rounded-full bg-white/90 text-slate-800 hover:bg-white flex items-center justify-center shadow-lg transition-all active:scale-90 pointer-events-auto">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                        </button>
                        <button @click="next()" class="w-9 h-9 rounded-full bg-white/90 text-slate-800 hover:bg-white flex items-center justify-center shadow-lg transition-all active:scale-90 pointer-events-auto">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                        </button>
                    </div>
                </template>

                <!-- Dots Indicators -->
                <template x-if="slides.length > 1">
                    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2">
                        <template x-for="(slide, index) in slides" :key="index">
                            <button @click="activeSlide = index" 
                                    class="h-1.5 rounded-full transition-all duration-300"
                                    :class="activeSlide === index ? 'w-5 bg-primary shadow-sm' : 'w-1.5 bg-white/50 hover:bg-white'"></button>
                        </template>
                    </div>
                </template>

                <!-- Image Counter Tag -->
                <div class="absolute top-4 right-4 bg-black/60 backdrop-blur-md text-white text-[10px] font-extrabold px-3 py-1.5 rounded-full uppercase tracking-wider flex items-center gap-1 shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    <span x-text="(activeSlide + 1) + '/' + slides.length"></span> Hình ảnh
                </div>
            </div>
        </div>

        <!-- MAIN LAYOUT DETAIL & SIDEBAR -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            
            <!-- LEFT COLUMN: PROPERTY INFORMATION (lg:col-span-8) -->
            <div class="lg:col-span-8 space-y-10 bg-white p-8 rounded-[36px] border border-slate-100 shadow-sm">
                
                <!-- Title, Badges, Pricing -->
                <div class="space-y-4">
                    <div class="flex flex-wrap gap-2 items-center">
                        <span class="px-3.5 py-1.5 rounded-full text-[10px] font-black tracking-widest bg-emerald-500 text-white uppercase shadow-sm flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            Xác thực 3 Thật
                        </span>
                        <span class="bg-slate-100 text-slate-600 text-[10px] font-extrabold px-3 py-1.5 rounded-full">
                            {{ $property['rstype'] }}
                        </span>
                        <span class="text-xs text-slate-400 font-bold ml-auto" x-text="'ID: ' + {{ $property['id'] }}"></span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-snug">
                        {{ $property['title'] }}
                    </h1>

                    <p class="text-xs font-semibold text-slate-400 flex items-center gap-1 pb-4 border-b border-slate-50">
                        <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        {{ $property['address'] }}
                    </p>
                </div>

                <!-- Pricing & Key specs (Moso styled) -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 py-6 border-b border-slate-50">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Giá chính chủ</span>
                        <span class="text-xl font-black text-primary leading-none">{{ $property['formatedPrice'] ?? 'Liên hệ' }}</span>
                    </div>
                    <div class="flex flex-col border-l border-slate-100 pl-6">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Diện tích</span>
                        <span class="text-xl font-black text-slate-800 leading-none">{{ number_format($property['total_area'] ?? 45.0, 1) }}m²</span>
                    </div>
                    <div class="flex flex-col border-l border-slate-100 pl-6">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Phòng ngủ</span>
                        <span class="text-xl font-black text-slate-800 leading-none">{{ $property['bed'] ?? 1 }} PN</span>
                    </div>
                    <div class="flex flex-col border-l border-slate-100 pl-6">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Phòng tắm</span>
                        <span class="text-xl font-black text-slate-800 leading-none">{{ $property['bath'] ?? 1 }} WC</span>
                    </div>
                </div>

                <!-- Additional Features Grid -->
                <div class="space-y-4">
                    <h3 class="text-base font-bold text-slate-800">Thông số bổ sung</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 p-6 rounded-3xl text-xs font-semibold text-slate-600">
                        <div class="flex justify-between py-1 border-b border-slate-200/40">
                            <span class="text-slate-400">Số tầng</span>
                            <span class="text-slate-700 font-extrabold">{{ $property['floors'] ?? 1 }} tầng</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-200/40">
                            <span class="text-slate-400">Hướng nhà</span>
                            <span class="text-slate-700 font-extrabold">{{ $property['direction'] ?? 'Chưa xác định' }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-200/40">
                            <span class="text-slate-400">Đăng bởi</span>
                            <span class="text-slate-700 font-extrabold">Chủ nhà trực tiếp</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-200/40">
                            <span class="text-slate-400">Tình trạng pháp lý</span>
                            <span class="text-emerald-600 font-extrabold">Đã kiểm duyệt</span>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="space-y-4 pb-8 border-b border-slate-50">
                    <h3 class="text-base font-bold text-slate-800">Mô tả bất động sản</h3>
                    <div class="text-sm text-slate-500 leading-relaxed space-y-4 font-medium">
                        <p>Chào mừng quý khách đến với căn hộ cao cấp sang trọng của chúng tôi. Căn nhà có kết cấu vô cùng kiên cố, tọa lạc tại vị trí cực kỳ đắc địa ngay trong trung tâm thành phố. Hướng nhà đón gió trời tự nhiên thoáng mát suốt cả ngày dài, thiết kế cực kỳ hiện đại với nội thất nhập khẩu cao cấp.</p>
                        <p>Xung quanh đầy đủ các tiện ích dân sinh vượt trội: trường học các cấp, siêu thị, bệnh viện lớn, khu trung tâm thương mại thương mại giải trí bậc nhất. Giao thông đi lại vô cùng thuận tiện, không bao giờ ngập nước, an ninh bảo vệ nghiêm ngặt 24/7. Thích hợp cho hộ gia đình định cư lâu dài hoặc người đi làm làm việc tại các quận trung tâm.</p>
                    </div>
                </div>

                <!-- DETAILED MAPLIBRE GPS MAP -->
                <div class="space-y-4">
                    <h3 class="text-base font-bold text-slate-800">Vị trí địa lý chính xác (3 Thật)</h3>
                    <p class="text-xs text-slate-400">Địa chỉ bất động sản được ghim định vị GPS chính xác trên hệ thống bản đồ số MapLibre.</p>
                    <div class="h-[280px] rounded-3xl overflow-hidden border border-slate-100 shadow-sm relative">
                        <!-- Expand Button -> Redirects to the big map -->
                        <a :href="'/properties?focus=' + property.id" 
                           class="absolute top-3 left-3 z-10 bg-white/95 hover:bg-white text-slate-700 hover:text-primary font-extrabold text-xs px-3.5 py-2 rounded-2xl shadow-lg border border-slate-100 flex items-center gap-1.5 transition-all duration-200 active:scale-95">
                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 01.553-.894L9 2l6 3 5.447-2.724A1 1 0 0121 3.168v10.764a1 1 0 01-.553.894L15 18l-6 2z" />
                            </svg>
                            <span>Mở rộng bản đồ</span>
                        </a>
                        <div id="property-detail-map" class="w-full h-full"></div>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: STICKY CONTACT & VIEWING APPOINTMENT FORM (lg:col-span-4) -->
            <div class="lg:col-span-4 lg:sticky lg:top-24 space-y-6">
                
                <!-- CONTACT & APPOINTMENT WIDGET -->
                <div class="bg-white rounded-[36px] p-6 border border-slate-100 shadow-premium space-y-6">
                    
                    <!-- Title/Action Header -->
                    <div class="flex justify-between items-center pb-4 border-b border-slate-50">
                        <h3 class="text-base font-black text-slate-800">Thông tin liên hệ</h3>
                        <div class="flex gap-2">
                            <!-- Share Button -->
                            <button @click="openShare('{{ url('/properties') }}/' + property.slug, property.title)" 
                                    class="w-9 h-9 rounded-full bg-slate-50 text-slate-500 hover:bg-slate-100 hover:text-primary flex items-center justify-center shadow-md transition-all duration-200 active:scale-95">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 10.742l4.622-2.311m0 0a3 3 0 10-2.667-1.772a3 3 0 002.667 1.772zm0 6.518l-4.623-2.311a3 3 0 11-2.667-1.772a3 3 0 012.667 1.772zm1.144 0a3 3 0 112.667 1.772a3 3 0 01-2.667-1.772z" />
                                </svg>
                            </button>
                            <!-- Favorite Button -->
                            <button @click="toggleFav()" 
                                    class="w-9 h-9 rounded-full flex items-center justify-center shadow-md transition-all duration-200"
                                    :class="isFavorite ? 'bg-red-500 text-white animate-heart-pop' : 'bg-slate-50 text-slate-500 hover:bg-slate-100 hover:text-red-500'">
                                <svg class="w-4.5 h-4.5" :fill="isFavorite ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Host Card Details -->
                    <div class="flex items-center gap-3.5 bg-slate-50/70 p-3 rounded-2xl">
                        <div class="w-12 h-12 rounded-full border border-slate-100 overflow-hidden bg-white">
                            <img src="{{ $property['sale']['avatar'] ?? 'https://api.dicebear.com/7.x/adventurer/svg?seed=Minh' }}" alt="Host Avatar" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-extrabold text-sm text-slate-800">{{ $property['sale']['name'] ?? 'Anh Minh' }}</h4>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wide">Chủ nhà chính chủ</p>
                        </div>
                    </div>

                    <!-- Direct contact widgets -->
                    <div class="grid grid-cols-2 gap-3">
                        <a href="tel:{{ $property['sale']['phone'] ?? '0932030958' }}" class="bg-primary text-white font-extrabold text-xs py-3.5 rounded-2xl shadow-md btn-hover-premium text-center flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            Gọi điện
                        </a>
                        <a href="https://zalo.me/{{ $property['sale']['phone'] ?? '0932030958' }}" target="_blank" class="bg-white border border-slate-200 text-primary font-extrabold text-xs py-3.5 rounded-2xl shadow-sm btn-hover-premium text-center flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                            Nhắn Zalo
                        </a>
                    </div>

                    <hr class="border-slate-50">

                    <!-- APPOINTMENT SCHEDULER FORM -->
                    <div class="space-y-4">
                        <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider">Đặt lịch xem nhà thực tế</h4>
                        
                        <!-- Success Alert Message -->
                        <div x-show="isApptSuccess" x-cloak class="bg-green-50 border border-green-200 text-green-800 p-4 rounded-2xl text-xs text-center space-y-1">
                            <p class="font-bold">Đặt lịch xem thành công!</p>
                            <p class="text-slate-500">Lịch của bạn đã được chuyển đến chủ nhà để duyệt. Đang chuyển hướng...</p>
                        </div>
                        
                        <form @submit.prevent="bookAppointment()" x-show="!isApptSuccess" class="space-y-4">
                            <!-- Date Selector -->
                            <div>
                                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Chọn ngày hẹn xem</label>
                                <input type="date" x-model="apptDate" required class="w-full bg-slate-50 border border-slate-200/50 rounded-xl px-3.5 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700">
                            </div>

                            <!-- Time Selector -->
                            <div>
                                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Chọn giờ hẹn xem</label>
                                <input type="time" x-model="apptTime" required class="w-full bg-slate-50 border border-slate-200/50 rounded-xl px-3.5 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700">
                            </div>

                            <!-- Contact details -->
                            <div class="space-y-3 pt-1">
                                <input type="text" x-model="apptName" placeholder="Họ và tên của bạn" required class="w-full bg-slate-50 border border-slate-200/50 rounded-xl px-3.5 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-all">
                                <input type="tel" x-model="apptPhone" placeholder="Số điện thoại của bạn" required class="w-full bg-slate-50 border border-slate-200/50 rounded-xl px-3.5 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-all">
                                <input type="email" x-model="apptEmail" placeholder="Email của bạn (không bắt buộc)" class="w-full bg-slate-50 border border-slate-200/50 rounded-xl px-3.5 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-all">
                                <textarea x-model="apptNote" placeholder="Ghi chú thêm (ví dụ: giờ xem cụ thể, số lượng người...)" rows="3" class="w-full bg-slate-50 border border-slate-200/50 rounded-xl px-3.5 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-all resize-none"></textarea>
                            </div>

                            <button type="submit" class="w-full bg-primary text-white font-extrabold py-4 rounded-2xl shadow-md btn-hover-premium text-xs">
                                Gửi yêu cầu đặt lịch hẹn
                            </button>
                        </form>
                    </div>

                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('propertyDetail', (propertyData) => ({
            property: propertyData,
            favorites: [],
            appointments: [],
            isFavorite: false,
            
            // Form State
            apptDate: '',
            apptTime: '',
            apptName: '',
            apptPhone: '',
            apptEmail: '',
            apptNote: '',
            isApptSuccess: false,
            
            init() {
                // Prefill user details if logged in
                const savedUser = localStorage.getItem('nks_user');
                if (savedUser) {
                    const u = JSON.parse(savedUser);
                    this.apptName = u.name || '';
                    this.apptPhone = u.phone || '';
                    this.apptEmail = u.email || '';
                }

                // Load favorites
                const savedFavs = localStorage.getItem('nks_favorites');
                if (savedFavs) {
                    this.favorites = JSON.parse(savedFavs);
                    this.isFavorite = this.favorites.some(f => f.id === this.property.id);
                }
                
                // Sync favorites
                window.addEventListener('nks-fav-change', () => {
                    const saved = localStorage.getItem('nks_favorites');
                    if (saved) {
                        this.favorites = JSON.parse(saved);
                        this.isFavorite = this.favorites.some(f => f.id === this.property.id);
                    }
                });

                // Init map
                this.initPropertyMap();
            },
            
            async toggleFav() {
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
                                property_id: this.property.id > 100 ? this.property.id : null,
                                external_property_id: this.property.id <= 100 ? String(this.property.id) : null
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

                const index = this.favorites.findIndex(f => f.id === this.property.id);
                if (index > -1) {
                    this.favorites.splice(index, 1);
                    this.isFavorite = false;
                } else {
                    this.favorites.push({
                        id: this.property.id,
                        title: this.property.title,
                        slug: this.property.slug,
                        featureimg: this.property.featureimg,
                        address: this.property.address,
                        rstype: this.property.rstype,
                        formatedPrice: this.property.formatedPrice
                    });
                    this.isFavorite = true;
                }
                localStorage.setItem('nks_favorites', JSON.stringify(this.favorites));
                window.dispatchEvent(new CustomEvent('nks-fav-change'));
                window.dispatchEvent(new CustomEvent('nks-login-change'));
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
                            property_id: String(this.property.id),
                            appt_name: this.apptName,
                            appt_phone: this.apptPhone,
                            email: this.apptEmail,
                            note: this.apptNote,
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
                            property_title: this.property.title,
                            property_slug: this.property.slug,
                            date: this.apptDate,
                            time: this.apptTime,
                            name: this.apptName,
                            phone: this.apptPhone,
                            email: this.apptEmail,
                            note: this.apptNote,
                            status: 'confirmed',
                            host_name: this.property.sale?.name || 'Anh Minh',
                            host_phone: this.property.sale?.phone || '0932030958'
                        };
                        
                        currentAppts.push(newAppt);
                        localStorage.setItem('nks_appointments', JSON.stringify(currentAppts));
                        
                        this.isApptSuccess = true;
                        this.apptDate = '';
                        this.apptTime = '';
                        this.apptNote = '';
                        
                        setTimeout(() => {
                            this.isApptSuccess = false;
                            window.location.href = '/profile?tab=appointments';
                        }, 2000);
                    } else {
                        alert('Đặt lịch hẹn xem nhà không thành công.');
                    }
                } catch (e) {
                    alert('Lỗi kết nối máy chủ CSDL.');
                }
            },
            
            initPropertyMap() {
                const geoString = this.property.geolocation;
                if (!geoString) return;
                
                const [lat, lng] = geoString.split(',').map(parseFloat);
                if (isNaN(lat) || isNaN(lng)) return;
                
                const map = new maplibregl.Map({
                    container: 'property-detail-map',
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
                
                map.addControl(new maplibregl.NavigationControl(), 'top-right');
                
                // Add Marker
                new maplibregl.Marker()
                    .setLngLat([lng, lat])
                    .addTo(map);
            }
        }));
    });
</script>
@endsection
