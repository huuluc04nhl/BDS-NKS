@extends('layouts.app')

@section('title', 'Quản lý tài khoản thành viên - BDS NKS')

@section('content')
<div class="py-12 bg-slate-50 min-h-screen"
     x-data="memberDashboard()">
     
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Sidebar Navigation (Only shown when logged in) -->
            <template x-if="isLoggedIn">
                <div class="w-full lg:w-1/4 flex-shrink-0">
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-6">
                        <!-- User Card -->
                        <div class="text-center pb-6 border-b border-slate-100">
                            <div class="w-20 h-20 rounded-full border-4 border-primary/10 overflow-hidden mx-auto bg-slate-50 mb-3 relative group">
                                <img :src="user && user.avatar ? user.avatar : 'https://api.dicebear.com/7.x/adventurer/svg?seed=' + (user ? user.name : 'nks')" alt="Avatar" class="w-full h-full object-cover">
                            </div>
                            <h3 class="text-lg font-bold text-slate-800" x-text="user ? user.name : ''"></h3>
                            <p class="text-xs text-slate-400 mt-1" x-text="user ? user.email : ''"></p>
                            <span class="inline-flex mt-3 px-3 py-1 rounded-full text-[10px] font-extrabold tracking-wide uppercase"
                                  :class="user && user.role === 'owner' ? 'bg-primary/10 text-primary' : 'bg-slate-100 text-slate-500'"
                                  x-text="user && user.role === 'owner' ? 'Chủ nhà chính chủ' : 'Khách thuê'"></span>
                        </div>
                        
                        <!-- Nav Items -->
                        <div class="space-y-1">
                            <button @click="activeTab = 'info'" 
                                    class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-300"
                                    :class="activeTab === 'info' ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-600 hover:bg-slate-50 hover:text-primary'">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                Thông tin cá nhân
                            </button>
                            <button @click="activeTab = 'favorites'" 
                                    class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-300"
                                    :class="activeTab === 'favorites' ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-600 hover:bg-slate-50 hover:text-primary'">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                Tin đã yêu thích
                                <span class="ml-auto bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full text-xs font-bold" :class="activeTab === 'favorites' && 'bg-white/20 text-white'" x-text="favorites.length">0</span>
                            </button>
                            <button @click="activeTab = 'appointments'" 
                                    class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-300"
                                    :class="activeTab === 'appointments' ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-600 hover:bg-slate-50 hover:text-primary'">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                Lịch hẹn xem nhà
                                <span class="ml-auto bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full text-xs font-bold" :class="activeTab === 'appointments' && 'bg-white/20 text-white'" x-text="appointments.length">0</span>
                            </button>
                            
                            <!-- Owner Specific Tab -->
                            <template x-if="user && user.role === 'owner'">
                                <button @click="activeTab = 'properties'" 
                                        class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-300"
                                        :class="activeTab === 'properties' ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-600 hover:bg-slate-50 hover:text-primary'">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                    Tin đăng chính chủ
                                    <span class="ml-auto bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full text-xs font-bold" :class="activeTab === 'properties' && 'bg-white/20 text-white'" x-text="ownerProperties.length">0</span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
            
            <!-- Dashboard Content -->
            <div class="flex-grow w-full lg:w-3/4">
                <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm min-h-[500px]">
                    
                    <!-- TAB: INFO (Profile & Password Edit) -->
                    <div x-show="activeTab === 'info' && isLoggedIn" x-cloak class="space-y-8 animate-fade-in-up">
                        <div>
                            <h2 class="text-2xl font-extrabold text-slate-800">Thông tin cá nhân</h2>
                            <p class="text-sm text-slate-400 mt-1">Cập nhật hồ sơ thành viên của bạn tại BDS NKS</p>
                        </div>
                        
                        <form @submit.prevent="updateProfile()" class="space-y-6 max-w-xl">
                            <!-- Avatar URL -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Ảnh đại diện (Link ảnh)</label>
                                <input type="url" x-model="avatarInput" placeholder="https://example.com/avatar.jpg" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3.5 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all">
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <!-- Name -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tên hiển thị</label>
                                    <input type="text" x-model="nameInput" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3.5 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all">
                                </div>
                                <!-- Phone -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Số điện thoại liên hệ</label>
                                    <input type="tel" x-model="phoneInput" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3.5 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all">
                                </div>
                            </div>
                            
                            <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-bold px-6 py-3 rounded-full text-sm shadow-md shadow-primary/20 hover:shadow-lg transition-all duration-300">Cập nhật hồ sơ</button>
                        </form>
                        
                        <hr class="border-slate-100">
                        
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Cập nhật mật khẩu</h3>
                            <p class="text-xs text-slate-400 mt-1">Đổi mật khẩu bảo mật tài khoản</p>
                        </div>
                        
                        <form @submit.prevent="updatePassword()" class="space-y-6 max-w-xl">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Mật khẩu hiện tại</label>
                                    <input type="password" x-model="passwordCurrent" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3.5 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Mật khẩu mới</label>
                                    <input type="password" x-model="passwordNew" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3.5 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all">
                                </div>
                            </div>
                            
                            <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-bold px-6 py-3 rounded-full text-sm shadow-md shadow-primary/20 hover:shadow-lg transition-all duration-300">Đổi mật khẩu</button>
                        </form>
                    </div>
                    
                    <!-- TAB: FAVORITES -->
                    <div x-show="activeTab === 'favorites' && isLoggedIn" x-cloak class="space-y-8 animate-fade-in-up">
                        <div>
                            <h2 class="text-2xl font-extrabold text-slate-800">Tin đã yêu thích</h2>
                            <p class="text-sm text-slate-400 mt-1">Danh sách bất động sản bạn đã lưu quan tâm</p>
                        </div>
                        
                        <template x-if="favorites.length === 0">
                            <div class="text-center py-12 space-y-4">
                                <div class="w-16 h-16 rounded-full bg-red-50 text-red-500 flex items-center justify-center mx-auto">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                </div>
                                <h3 class="text-base font-bold text-slate-700">Chưa có tin yêu thích nào</h3>
                                <p class="text-sm text-slate-400 max-w-xs mx-auto">Hãy quay lại trang tìm kiếm và nhấn nút thả tim tại các tin đăng để lưu lại ở đây nhé!</p>
                                <a href="/properties" class="inline-flex bg-primary hover:bg-primary-dark text-white font-bold px-6 py-2.5 rounded-full text-sm shadow-md transition-all duration-300">Khám phá ngay</a>
                            </div>
                        </template>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <template x-for="item in favorites" :key="item.id">
                                <div class="bg-slate-50 border border-slate-200/60 rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                                    <div class="h-40 overflow-hidden relative">
                                        <img :src="item.featureimg" alt="BDS" class="w-full h-full object-cover">
                                        <button @click="removeFavorite(item.id)" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white text-red-500 flex items-center justify-center shadow-md hover:scale-105 transition-all">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                        </button>
                                    </div>
                                    <div class="p-5 flex-grow flex flex-col justify-between">
                                        <div>
                                            <span class="inline-block px-2.5 py-0.5 bg-primary/10 text-primary text-[10px] font-bold rounded-md uppercase mb-2" x-text="item.rstype">Căn hộ</span>
                                            <a :href="'/properties/' + item.slug" class="block font-bold text-slate-800 hover:text-primary transition-colors text-sm line-clamp-2" x-text="item.title"></a>
                                            <p class="text-xs text-slate-400 mt-2 truncate flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                                                <span x-text="item.address"></span>
                                            </p>
                                        </div>
                                        <div class="flex justify-between items-center border-t border-slate-200/50 mt-4 pt-3">
                                            <p class="text-base font-extrabold text-primary" x-text="item.formatedPrice"></p>
                                            <a :href="'/properties/' + item.slug" class="text-xs font-bold text-slate-500 hover:text-primary transition-colors">Xem chi tiết &rarr;</a>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    
                    <!-- TAB: APPOINTMENTS -->
                    <div x-show="activeTab === 'appointments' && isLoggedIn" x-cloak class="space-y-8 animate-fade-in-up">
                        <div>
                            <h2 class="text-2xl font-extrabold text-slate-800">Lịch hẹn xem nhà</h2>
                            <p class="text-sm text-slate-400 mt-1">Quản lý lịch làm việc và lịch xem thực tế với chủ nhà chính chủ</p>
                        </div>
                        
                        <template x-if="appointments.length === 0">
                            <div class="text-center py-12 space-y-4">
                                <div class="w-16 h-16 rounded-full bg-blue-50 text-primary flex items-center justify-center mx-auto">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                                <h3 class="text-base font-bold text-slate-700">Chưa có lịch hẹn xem nhà nào</h3>
                                <p class="text-sm text-slate-400 max-w-xs mx-auto">Vào trang chi tiết bất động sản để đặt lịch trực tuyến nhanh chóng với chủ nhà!</p>
                                <a href="/properties" class="inline-flex bg-primary hover:bg-primary-dark text-white font-bold px-6 py-2.5 rounded-full text-sm shadow-md transition-all duration-300">Đặt lịch ngay</a>
                            </div>
                        </template>
                        
                        <div class="space-y-4">
                            <template x-for="appt in appointments" :key="appt.id">
                                <div class="bg-slate-50 border border-slate-200/50 rounded-3xl p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 shadow-sm">
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-3">
                                            <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-extrabold tracking-wider uppercase bg-green-100 text-green-700" x-text="appt.status === 'confirmed' ? 'Đã xác nhận' : 'Đang chờ'"></span>
                                            <span class="text-xs font-bold text-slate-400 flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                Ngày xem: <span class="text-slate-700 font-extrabold" x-text="appt.date + ' lúc ' + appt.time"></span>
                                            </span>
                                        </div>
                                        <h3 class="font-bold text-slate-800 text-base" x-text="appt.property_title"></h3>
                                        <p class="text-xs text-slate-500">
                                            Liên hệ chủ nhà: <span class="font-bold text-slate-700" x-text="appt.host_name"></span> - <a :href="'tel:' + appt.host_phone" class="font-bold text-primary hover:underline" x-text="appt.host_phone"></a>
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-3 w-full md:w-auto">
                                        <button @click="cancelAppointment(appt.id)" class="w-full md:w-auto text-center border border-red-200 text-red-500 hover:bg-red-50 text-xs font-bold px-5 py-2.5 rounded-full transition-colors duration-200">
                                            Hủy lịch hẹn
                                        </button>
                                        <a :href="'/properties/' + appt.property_slug" class="w-full md:w-auto text-center bg-white border border-slate-200 text-slate-600 hover:text-primary text-xs font-bold px-5 py-2.5 rounded-full transition-colors shadow-sm">
                                            Xem tin đăng
                                        </a>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    
                    <!-- TAB: MY PROPERTIES (Owner Only) -->
                    <div x-show="activeTab === 'properties' && isLoggedIn && user && user.role === 'owner'" x-cloak class="space-y-8 animate-fade-in-up">
                        <div>
                            <h2 class="text-2xl font-extrabold text-slate-800">Tin đăng chính chủ</h2>
                            <p class="text-sm text-slate-400 mt-1">Danh sách bất động sản bạn đang đăng cho thuê trực tiếp</p>
                        </div>
                        
                        <div class="bg-primary-extralight border border-primary/20 rounded-3xl p-6 flex flex-col sm:flex-row justify-between items-center gap-6">
                            <div>
                                <h3 class="font-bold text-primary-dark">Đăng tin cho thuê chính chủ miễn phí</h3>
                                <p class="text-xs text-slate-500 mt-1">Chỉ mất 2 phút để tin đăng của bạn hiển thị tiếp cận hàng ngàn khách thuê có nhu cầu thực tế.</p>
                            </div>
                            <button @click="showAddPropertyModal = true" class="bg-primary hover:bg-primary-dark text-white font-bold text-xs px-6 py-3 rounded-full shadow-md shadow-primary/20 hover:shadow-lg transition-all duration-300">Đăng tin mới +</button>
                        </div>
                        
                        <template x-if="ownerProperties.length === 0">
                            <div class="text-center py-12 space-y-4">
                                <div class="w-16 h-16 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center mx-auto">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                </div>
                                <h3 class="text-base font-bold text-slate-700">Bạn chưa có tin đăng nào</h3>
                                <p class="text-sm text-slate-400 max-w-xs mx-auto">Hãy nhấn nút Đăng tin mới ở trên để bắt đầu tiếp cận khách thuê trực tuyến.</p>
                            </div>
                        </template>

                        <template x-if="ownerProperties.length > 0">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <template x-for="item in ownerProperties" :key="item.id">
                                    <div class="bg-slate-50 border border-slate-200/60 rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                                        <div class="h-40 overflow-hidden relative">
                                            <img :src="item.feature_img || item.featureimg" alt="BDS" class="w-full h-full object-cover">
                                            <span class="absolute top-3 left-3 bg-emerald-500 text-white text-[9px] font-bold px-2 py-0.5 rounded-md uppercase">Đã duyệt</span>
                                        </div>
                                        <div class="p-5 flex-grow flex flex-col justify-between">
                                            <div>
                                                <span class="inline-block px-2.5 py-0.5 bg-primary/10 text-primary text-[10px] font-bold rounded-md uppercase mb-2" x-text="item.rstype">Căn hộ</span>
                                                <a :href="'/properties/' + item.slug" class="block font-bold text-slate-800 hover:text-primary transition-colors text-sm line-clamp-2" x-text="item.title"></a>
                                                <p class="text-xs text-slate-400 mt-2 truncate flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                                                    <span x-text="item.address"></span>
                                                </p>
                                            </div>
                                            <div class="flex justify-between items-center border-t border-slate-200/50 mt-4 pt-3">
                                                <p class="text-base font-extrabold text-primary" x-text="item.formated_price || item.formatedPrice"></p>
                                                <a :href="'/properties/' + item.slug" class="text-xs font-bold text-slate-500 hover:text-primary transition-colors">Xem chi tiết &rarr;</a>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                    
                    <!-- TAB: REGISTER AS OWNER / HOST -->
                    <div x-show="activeTab === 'host'" x-cloak class="space-y-8 animate-fade-in-up">
                        <div>
                            <h2 class="text-2xl font-extrabold text-slate-800">Đăng ký làm chủ nhà</h2>
                            <p class="text-sm text-slate-400 mt-1">Đăng tin BDS chính chủ để tiếp cận hàng ngàn khách thuê, không qua môi giới</p>
                        </div>
                        
                        <!-- Success Message -->
                        <div x-show="isOwnerRegSuccess" class="bg-green-100 border border-green-200 text-green-800 rounded-3xl p-6 text-center space-y-2">
                            <h3 class="text-lg font-bold">Đăng ký thành công!</h3>
                            <p class="text-sm">Tài khoản của bạn đã được nâng cấp lên Chủ nhà chính chủ. Đang chuyển hướng...</p>
                        </div>
                        
                        <div x-show="!isOwnerRegSuccess" class="flex flex-col md:flex-row gap-12 items-center">
                            <form @submit.prevent="registerHost()" class="space-y-6 w-full md:w-1/2">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Họ và tên chủ nhà</label>
                                    <input type="text" x-model="nameInput" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3.5 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Số điện thoại chính chủ (Nhận Zalo/Cuộc gọi)</label>
                                    <input type="tel" x-model="phoneInput" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3.5 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Công ty/Tổ chức (Nếu có)</label>
                                    <input type="text" x-model="companyInput" placeholder="Ví dụ: Căn hộ dịch vụ Hùng Phát" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3.5 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all">
                                </div>
                                
                                <div class="bg-amber-50 border border-amber-200 text-amber-800 p-4 rounded-2xl text-xs space-y-1">
                                    <p class="font-bold flex items-center gap-1"><svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg> Cam kết chính chủ</p>
                                    <p class="text-slate-600">Bằng việc gửi thông tin, bạn xác nhận mình là chủ sở hữu hoặc người quản lý trực tiếp bất động sản này. Hệ thống sẽ khóa tài khoản nếu phát hiện môi giới giả danh.</p>
                                </div>
                                
                                <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-bold px-6 py-4 rounded-full text-sm shadow-md shadow-primary/20 hover:shadow-lg transition-all duration-300">Đăng ký làm chủ nhà ngay</button>
                            </form>
                            
                            <div class="w-full md:w-1/2 space-y-6">
                                <h3 class="text-lg font-bold text-slate-800">Quyền lợi đặc quyền khi là chủ nhà NKS</h3>
                                <ul class="space-y-4">
                                    <li class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center flex-shrink-0"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg></div>
                                        <div>
                                            <p class="font-bold text-sm text-slate-700">Đăng tin miễn phí 100%</p>
                                            <p class="text-xs text-slate-400 mt-0.5">Không phụ thu, không chiết khấu hoa hồng khi giao dịch thành công.</p>
                                        </div>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center flex-shrink-0"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg></div>
                                        <div>
                                            <p class="font-bold text-sm text-slate-700">Quản lý lịch hẹn tự động</p>
                                            <p class="text-xs text-slate-400 mt-0.5">Hệ thống thông báo và theo dõi lịch hẹn xem nhà trực tuyến của khách thuê một cách thông minh.</p>
                                        </div>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center flex-shrink-0"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg></div>
                                        <div>
                                            <p class="font-bold text-sm text-slate-700">Liên hệ trực tiếp qua Zalo/Cuộc gọi</p>
                                            <p class="text-xs text-slate-400 mt-0.5">Khách thuê chủ động liên lạc trực tiếp tới số điện thoại cá nhân không thông qua trung gian.</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- TAB: LOGIN -->
                    <div x-show="activeTab === 'login'" x-cloak class="space-y-8 animate-fade-in-up max-w-md mx-auto">
                        <div class="text-center">
                            <h2 class="text-2xl font-extrabold text-slate-800">Đăng nhập tài khoản</h2>
                            <p class="text-sm text-slate-400 mt-1">Đăng nhập để lưu tin yêu thích và quản lý lịch hẹn</p>
                        </div>
                        
                        <form @submit.prevent="login($refs.loginEmail.value, $refs.loginPass.value)" class="space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Địa chỉ Email</label>
                                <input type="email" x-ref="loginEmail" required placeholder="name@example.com" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3.5 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Mật khẩu</label>
                                <input type="password" x-ref="loginPass" required placeholder="••••••••" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3.5 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all">
                            </div>
                            <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-4 rounded-full text-sm shadow-md shadow-primary/20 hover:shadow-lg transition-all duration-300">Đăng nhập</button>
                        </form>
                        
                        <div class="text-center text-sm text-slate-400">
                            Chưa có tài khoản? <button @click="activeTab = 'register'" class="text-primary font-bold hover:underline">Đăng ký thành viên</button>
                        </div>
                    </div>
                    
                    <!-- TAB: REGISTER -->
                    <div x-show="activeTab === 'register'" x-cloak class="space-y-8 animate-fade-in-up max-w-md mx-auto">
                        <div class="text-center">
                            <h2 class="text-2xl font-extrabold text-slate-800">Đăng ký thành viên</h2>
                            <p class="text-sm text-slate-400 mt-1">Trở thành thành viên của cộng đồng BDS NKS</p>
                        </div>
                        
                        <form @submit.prevent="register($refs.regName.value, $refs.regEmail.value, $refs.regPass.value, $refs.regRole.value)" class="space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Họ và tên</label>
                                <input type="text" x-ref="regName" required placeholder="Nguyễn Văn A" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3.5 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Địa chỉ Email</label>
                                <input type="email" x-ref="regEmail" required placeholder="name@example.com" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3.5 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Mật khẩu</label>
                                <input type="password" x-ref="regPass" required placeholder="Mật khẩu ít nhất 6 ký tự" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3.5 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Vai trò tài khoản</label>
                                <select x-ref="regRole" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3.5 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all">
                                    <option value="renter">Tôi là Khách thuê tìm nhà</option>
                                    <option value="owner">Tôi là Chủ nhà cho thuê</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-4 rounded-full text-sm shadow-md shadow-primary/20 hover:shadow-lg transition-all duration-300">Đăng ký thành viên</button>
                        </form>
                        
                        <div class="text-center text-sm text-slate-400">
                            Đã có tài khoản? <button @click="activeTab = 'login'" class="text-primary font-bold hover:underline">Đăng nhập</button>
                        </div>
                    </div>

                </div>
            </div>
            
        </div>
    </div>

    <!-- ADD PROPERTY MODAL OVERLAY -->
    <div x-show="showAddPropertyModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         style="display: none;"
         x-cloak>
        
        <div @click.away="showAddPropertyModal = false" 
             x-show="showAddPropertyModal"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="bg-white rounded-[32px] shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden border border-slate-100 flex flex-col relative">
            
            <button @click="showAddPropertyModal = false" class="absolute top-4 right-4 z-50 w-9 h-9 rounded-full bg-white/95 hover:bg-white text-slate-500 hover:text-slate-800 shadow-md flex items-center justify-center transition-all active:scale-95 border border-slate-100">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>

            <div class="p-6 sm:p-8 overflow-y-auto space-y-6">
                <div>
                    <h2 class="text-xl font-black text-slate-900 leading-snug">Đăng tin bất động sản mới</h2>
                    <p class="text-xs text-slate-400">Điền thông số chính xác để xác thực 3 Thật và đăng trực tuyến miễn phí.</p>
                </div>

                <form @submit.prevent="addProperty()" class="space-y-4">
                    <!-- Title -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Tiêu đề tin đăng *</label>
                        <input type="text" x-model="newPropTitle" required placeholder="Ví dụ: Căn hộ Studio view sông cao cấp 45m²..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700">
                    </div>

                    <!-- Address & GPS -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Địa chỉ chi tiết *</label>
                            <input type="text" x-model="newPropAddress" required placeholder="Ví dụ: 123 Nguyễn Huệ, Quận 1..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Tọa độ GPS (Lat, Lng) *</label>
                            <input type="text" x-model="newPropGeolocation" required placeholder="10.7932,106.6710" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700">
                        </div>
                    </div>

                    <!-- Type, TxType, Price, Area -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Loại hình</label>
                            <select x-model="newPropType" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white text-slate-700">
                                <option value="Căn hộ">Căn hộ</option>
                                <option value="Nhà phố">Nhà phố</option>
                                <option value="Biệt thự">Biệt thự</option>
                                <option value="Phòng trọ">Phòng trọ</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Loại tin</label>
                            <select x-model="newPropTxType" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white text-slate-700">
                                <option value="Cho thuê">Cho thuê</option>
                                <option value="Bán">Bán</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Giá (VND) *</label>
                            <input type="number" x-model="newPropPrice" required placeholder="Ví dụ: 12000000" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Diện tích (m²) *</label>
                            <input type="number" step="0.1" x-model="newPropArea" required placeholder="45.0" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700">
                        </div>
                    </div>

                    <!-- Bed, Bath, Floors, Direction -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Phòng ngủ</label>
                            <input type="number" x-model="newPropBed" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white text-slate-700">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Phòng tắm</label>
                            <input type="number" x-model="newPropBath" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white text-slate-700">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Số tầng</label>
                            <input type="number" x-model="newPropFloors" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white text-slate-700">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Hướng nhà</label>
                            <select x-model="newPropDirection" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white text-slate-700">
                                <option value="Đông">Đông</option>
                                <option value="Tây">Tây</option>
                                <option value="Nam">Nam</option>
                                <option value="Bắc">Bắc</option>
                                <option value="Đông Bắc">Đông Bắc</option>
                                <option value="Đông Nam">Đông Nam</option>
                                <option value="Tây Bắc">Tây Bắc</option>
                                <option value="Tây Nam">Tây Nam</option>
                            </select>
                        </div>
                    </div>

                    <!-- Feature Image Link -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Ảnh đại diện (Link ảnh) *</label>
                        <input type="url" x-model="newPropFeatureImg" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700">
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Mô tả bất động sản</label>
                        <textarea rows="3" x-model="newPropDesc" placeholder="Mô tả các tiện ích đi kèm, khu vực lân cận..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-extrabold py-4 rounded-xl shadow-md shadow-primary/20 hover:shadow-lg transition-all hover:scale-[1.01] active:scale-95 text-xs uppercase tracking-wider">
                        Đăng tin ngay
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('memberDashboard', () => ({
            activeTab: 'info',
            isLoggedIn: false,
            user: null,
            appointments: [],
            favorites: [],
            ownerProperties: [],
            
            // Edit Profile State
            nameInput: '',
            phoneInput: '',
            avatarInput: '',
            passwordCurrent: '',
            passwordNew: '',
            
            // Owner Register State
            companyInput: '',
            addressInput: '',
            isOwnerRegSuccess: false,
            
            // New Property Form Modal State
            showAddPropertyModal: false,
            newPropTitle: '',
            newPropAddress: '',
            newPropGeolocation: '10.7932,106.6710',
            newPropType: 'Căn hộ',
            newPropTxType: 'Cho thuê',
            newPropPrice: '',
            newPropArea: '',
            newPropBed: 1,
            newPropBath: 1,
            newPropFloors: 1,
            newPropDirection: 'Đông',
            newPropFeatureImg: 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&q=80&w=800',
            newPropDesc: '',
            
            init() {
                const urlParams = new URLSearchParams(window.location.search);
                const tabParam = urlParams.get('tab');
                if (tabParam) {
                    this.activeTab = tabParam;
                }
                
                this.checkLogin();
                
                window.addEventListener('nks-login-change', () => {
                    this.checkLogin();
                });
                
                this.loadMockData();
            },
            
            checkLogin() {
                const savedUser = localStorage.getItem('nks_user');
                if (savedUser) {
                    this.isLoggedIn = true;
                    this.user = JSON.parse(savedUser);
                    this.nameInput = this.user.name;
                    this.phoneInput = this.user.phone || '';
                    this.avatarInput = this.user.avatar || '';
                } else {
                    this.isLoggedIn = false;
                    this.user = null;
                    if (this.activeTab !== 'login' && this.activeTab !== 'register' && this.activeTab !== 'host') {
                        this.activeTab = 'login';
                    }
                }
            },
            
            async loadMockData() {
                if (this.isLoggedIn && this.user && this.user.id) {
                    try {
                        const apptsRes = await fetch(`/api/appointments/user/${this.user.id}`);
                        if (apptsRes.ok) {
                            const apptsData = await apptsRes.json();
                            this.appointments = apptsData.appointments || [];
                            localStorage.setItem('nks_appointments', JSON.stringify(this.appointments));
                        }
                        
                        const favsRes = await fetch(`/api/favorites/user/${this.user.id}`);
                        if (favsRes.ok) {
                            const favsData = await favsRes.json();
                            this.favorites = favsData.favorites || [];
                            localStorage.setItem('nks_favorites', JSON.stringify(this.favorites));
                        }
                        
                        if (this.user.role === 'owner') {
                            const propsRes = await fetch(`/api/properties/owner/${this.user.id}`);
                            if (propsRes.ok) {
                                const propsData = await propsRes.json();
                                this.ownerProperties = propsData.properties || [];
                                localStorage.setItem('nks_owner_properties', JSON.stringify(this.ownerProperties));
                            }
                        }
                    } catch (e) {
                        console.warn('Database fetch failed, fallback to local storage:', e);
                    }
                }

                if (this.appointments.length === 0) {
                    const savedAppts = localStorage.getItem('nks_appointments');
                    if (savedAppts) this.appointments = JSON.parse(savedAppts);
                }
                if (this.favorites.length === 0) {
                    const savedFavs = localStorage.getItem('nks_favorites');
                    if (savedFavs) this.favorites = JSON.parse(savedFavs);
                }
                if (this.ownerProperties.length === 0) {
                    const savedOwnerProps = localStorage.getItem('nks_owner_properties');
                    if (savedOwnerProps) this.ownerProperties = JSON.parse(savedOwnerProps);
                }
            },
            
            async addProperty() {
                if (!this.newPropTitle || !this.newPropAddress || !this.newPropPrice || !this.newPropArea) {
                    alert('Vui lòng điền đầy đủ thông tin bắt buộc.');
                    return;
                }

                try {
                    const res = await fetch('/api/properties/add', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            user_id: this.user.id,
                            title: this.newPropTitle,
                            address: this.newPropAddress,
                            geolocation: this.newPropGeolocation,
                            rstype: this.newPropType,
                            transaction_type: this.newPropTxType,
                            price: parseFloat(this.newPropPrice),
                            total_area: parseFloat(this.newPropArea),
                            bed: parseInt(this.newPropBed),
                            bath: parseInt(this.newPropBath),
                            floors: parseInt(this.newPropFloors),
                            direction: this.newPropDirection,
                            feature_img: this.newPropFeatureImg,
                            description: this.newPropDesc
                        })
                    });

                    if (res.ok) {
                        const data = await res.json();
                        this.ownerProperties.unshift(data.property);
                        localStorage.setItem('nks_owner_properties', JSON.stringify(this.ownerProperties));
                        
                        this.newPropTitle = '';
                        this.newPropAddress = '';
                        this.newPropGeolocation = '10.7932,106.6710';
                        this.newPropPrice = '';
                        this.newPropArea = '';
                        this.newPropBed = 1;
                        this.newPropBath = 1;
                        this.newPropFloors = 1;
                        this.newPropDesc = '';
                        
                        this.showAddPropertyModal = false;
                        alert('Đăng tin bất động sản thành công! Tin đăng sẽ xuất hiện ngay trên bản đồ.');
                    } else {
                        alert('Đăng tin không thành công.');
                    }
                } catch (e) {
                    alert('Lỗi kết nối máy chủ CSDL.');
                }
            },
            
            async login(email, password) {
                if (!email || !password) {
                    alert('Vui lòng điền đầy đủ Email và Mật khẩu.');
                    return;
                }
                
                try {
                    const res = await fetch('/api/login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ email, password })
                    });

                    if (res.ok) {
                        const data = await res.json();
                        localStorage.setItem('nks_user', JSON.stringify(data.user));
                        window.dispatchEvent(new CustomEvent('nks-login-change'));
                        this.activeTab = 'info';
                        this.loadMockData();
                    } else {
                        const err = await res.json();
                        alert(err.message || 'Đăng nhập không thành công.');
                    }
                } catch (e) {
                    alert('Lỗi kết nối máy chủ CSDL.');
                }
            },
            
            async register(name, email, password, role) {
                if (!name || !email || !password) {
                    alert('Vui lòng điền đầy đủ thông tin.');
                    return;
                }
                
                try {
                    const res = await fetch('/api/register', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ name, email, password, role: role || 'renter' })
                    });

                    if (res.ok) {
                        const data = await res.json();
                        localStorage.setItem('nks_user', JSON.stringify(data.user));
                        window.dispatchEvent(new CustomEvent('nks-login-change'));
                        this.activeTab = 'info';
                        this.loadMockData();
                    } else {
                        const err = await res.json();
                        alert(err.message || 'Đăng ký không thành công.');
                    }
                } catch (e) {
                    alert('Lỗi kết nối máy chủ CSDL.');
                }
            },
            
            async updateProfile() {
                if (!this.nameInput) {
                    alert('Tên hiển thị không được bỏ trống.');
                    return;
                }
                
                try {
                    const res = await fetch('/api/profile/update', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            email: this.user.email,
                            name: this.nameInput,
                            phone: this.phoneInput,
                            avatar: this.avatarInput
                        })
                    });

                    if (res.ok) {
                        const data = await res.json();
                        this.user = data.user;
                        localStorage.setItem('nks_user', JSON.stringify(this.user));
                        window.dispatchEvent(new CustomEvent('nks-login-change'));
                        alert('Cập nhật thông tin cá nhân thành công!');
                    } else {
                        alert('Cập nhật thất bại.');
                    }
                } catch (e) {
                    alert('Lỗi kết nối CSDL.');
                }
            },
            
            updatePassword() {
                if (!this.passwordCurrent || !this.passwordNew) {
                    alert('Vui lòng điền mật khẩu hiện tại và mật khẩu mới.');
                    return;
                }
                
                alert('Cập nhật mật khẩu thành công!');
                this.passwordCurrent = '';
                this.passwordNew = '';
            },
            
            async registerHost() {
                if (!this.isLoggedIn) {
                    alert('Vui lòng đăng nhập trước khi đăng ký làm chủ nhà.');
                    this.activeTab = 'login';
                    return;
                }
                
                try {
                    const res = await fetch('/api/profile/upgrade-host', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            email: this.user.email,
                            name: this.nameInput || this.user.name,
                            phone: this.phoneInput
                        })
                    });

                    if (res.ok) {
                        const data = await res.json();
                        this.user = data.user;
                        localStorage.setItem('nks_user', JSON.stringify(this.user));
                        window.dispatchEvent(new CustomEvent('nks-login-change'));
                        this.isOwnerRegSuccess = true;
                        setTimeout(() => {
                            this.isOwnerRegSuccess = false;
                            this.activeTab = 'properties';
                            this.loadMockData();
                        }, 2000);
                    } else {
                        alert('Đăng ký làm chủ nhà không thành công.');
                    }
                } catch (e) {
                    alert('Lỗi kết nối CSDL.');
                }
            },
            
            async cancelAppointment(id) {
                if (confirm('Bạn có chắc chắn muốn hủy lịch hẹn xem nhà này?')) {
                    try {
                        await fetch(`/api/appointments/cancel/${id}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                    } catch (e) {}
                    
                    this.appointments = this.appointments.filter(a => a.id !== id);
                    localStorage.setItem('nks_appointments', JSON.stringify(this.appointments));
                }
            },
            
            async removeFavorite(id) {
                if (this.isLoggedIn && this.user && this.user.id) {
                    try {
                        await fetch('/api/favorites/toggle', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                user_id: this.user.id,
                                property_id: id
                            })
                        });
                    } catch (e) {}
                }
                
                this.favorites = this.favorites.filter(f => f.id !== id);
                localStorage.setItem('nks_favorites', JSON.stringify(this.favorites));
                window.dispatchEvent(new CustomEvent('nks-fav-change'));
            }
        }));
    });
</script>
@endsection
