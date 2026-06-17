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
                                  :class="user && user.role === 'admin' ? 'bg-rose-100 text-rose-600 border border-rose-200' : (user && user.role === 'owner' ? 'bg-primary/10 text-primary' : 'bg-slate-100 text-slate-500')"
                                  x-text="user && user.role === 'admin' ? 'Quản trị viên' : (user && user.role === 'owner' ? 'Chủ nhà chính chủ' : 'Khách thuê')"></span>
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
                            
                            <!-- User Specific Tabs -->
                            <template x-if="user && user.role !== 'admin'">
                                <div class="space-y-1">
                                    <!-- Owner Specific Tab -->
                                    <template x-if="user.role === 'owner'">
                                        <button @click="activeTab = 'properties'" 
                                                class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-300 mb-1"
                                                :class="activeTab === 'properties' ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-600 hover:bg-slate-50 hover:text-primary'">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                            Tin đăng chính chủ
                                            <span class="ml-auto bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full text-xs font-bold" :class="activeTab === 'properties' && 'bg-white/20 text-white'" x-text="ownerProperties.length">0</span>
                                        </button>
                                    </template>
                                    
                                    <button @click="activeTab = 'emails'; loadEmails();" 
                                            class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-300"
                                            :class="activeTab === 'emails' ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-600 hover:bg-slate-50 hover:text-primary'">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                        Hộp thư thông báo
                                        <span class="ml-auto bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full text-xs font-bold" :class="activeTab === 'emails' && 'bg-white/20 text-white'" x-text="emails.length">0</span>
                                    </button>
                                    <button @click="activeTab = 'chat'; startChatPolling();" 
                                            class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-300"
                                            :class="activeTab === 'chat' ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-600 hover:bg-slate-50 hover:text-primary'">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                                        Trò chuyện hỗ trợ
                                        <span class="ml-auto bg-rose-500 text-white px-2 py-0.5 rounded-full text-[10px] font-bold" x-show="unreadChatCount > 0" x-text="unreadChatCount">0</span>
                                    </button>
                                </div>
                            </template>

                            <!-- Admin Specific Tabs -->
                            <template x-if="user && user.role === 'admin'">
                                <div class="space-y-1">
                                    <button @click="activeTab = 'users'; loadAllUsers();" 
                                            class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-300"
                                            :class="activeTab === 'users' ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-600 hover:bg-slate-50 hover:text-primary'">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                        Quản lý thành viên
                                        <span class="ml-auto bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full text-xs font-bold" :class="activeTab === 'users' && 'bg-white/20 text-white'" x-text="allUsers.length">0</span>
                                    </button>
                                    <button @click="activeTab = 'emails_admin'; loadAdminEmails();" 
                                            class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-300"
                                            :class="activeTab === 'emails_admin' ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-600 hover:bg-slate-50 hover:text-primary'">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                        Nhật ký Email
                                        <span class="ml-auto bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full text-xs font-bold" :class="activeTab === 'emails_admin' && 'bg-white/20 text-white'" x-text="adminEmails.length">0</span>
                                    </button>
                                    <button @click="activeTab = 'chat_admin'; startAdminChatPolling();" 
                                            class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-300"
                                            :class="activeTab === 'chat_admin' ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-600 hover:bg-slate-50 hover:text-primary'">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                                        CSKH & Hỗ trợ Chat
                                        <span class="ml-auto bg-rose-500 text-white px-2 py-0.5 rounded-full text-[10px] font-bold" x-show="adminUnreadChatCount > 0" x-text="adminUnreadChatCount">0</span>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
            
            <!-- Dashboard Content -->
            <div class="flex-grow w-full lg:w-3/4">
                <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm min-h-[500px]">
                    
                    <!-- TAB: INFO (Profile & Password Edit) -->
                    <div x-show="activeTab === 'info' && isLoggedIn"
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200 transform"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-4"
                         x-cloak class="space-y-8">
                        <!-- User Header Card -->
                        <div class="flex flex-col sm:flex-row items-center gap-6 pb-6 border-b border-slate-100 mb-6">
                            <div class="relative group cursor-pointer w-24 h-24 rounded-full overflow-hidden border-4 border-primary/10 shadow-sm" @click="activeStep = 3">
                                <img :src="user && user.avatar ? user.avatar : 'https://api.dicebear.com/7.x/adventurer/svg?seed=' + (user ? user.name : 'nks')" alt="Avatar" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all duration-200">
                                    <span class="text-[10px] text-white font-extrabold uppercase tracking-wider">Đổi ảnh</span>
                                </div>
                            </div>
                            <div class="text-center sm:text-left space-y-1 w-full">
                                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2.5">
                                    <h3 class="text-xl font-black text-slate-800" x-text="user ? user.name : 'Thành viên NKS'"></h3>
                                    <span class="inline-flex items-center gap-1 bg-amber-500/10 text-amber-600 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wide border border-amber-200/50">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span x-text="(point || 0) + ' điểm'"></span>
                                    </span>
                                </div>
                                <p class="text-xs text-slate-400 font-medium" x-text="user ? user.email : ''"></p>
                                <div class="flex items-center justify-center sm:justify-start gap-3 mt-3 pt-2">
                                    <button type="button" @click="activeStep = 2" class="inline-flex items-center gap-1 text-slate-500 hover:text-primary transition-all text-xs font-bold bg-white border border-slate-200 px-3.5 py-2 rounded-2xl shadow-xs hover:shadow-sm">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                        </svg>
                                        Mật khẩu
                                    </button>
                                    <button type="button" @click="cccdNumberInput = idNumberInput; cccdDateInput = idDateInput; cccdPlaceInput = idPlaceInput; activeStep = 4" class="inline-flex items-center gap-1 text-slate-500 hover:text-primary transition-all text-xs font-bold bg-white border border-slate-200 px-3.5 py-2 rounded-2xl shadow-xs hover:shadow-sm">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.378 0 2.5-1.122 2.5-2.5S10.378 9 9 9m9 2h-3m3 4h-3" />
                                        </svg>
                                        CCCD / CMND
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Stepper Progress Bar -->
                        <div class="mb-8 bg-slate-50 border border-slate-200/60 rounded-3xl p-6">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                <!-- Step 1 Indicator -->
                                <div class="flex items-center gap-3 cursor-pointer" @click="activeStep = 1">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs transition-all duration-300"
                                         :class="activeStep === 1 ? 'bg-primary text-white ring-4 ring-primary/20' : (activeStep > 1 ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-500')">
                                        <template x-if="activeStep > 1">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </template>
                                        <template x-if="activeStep <= 1">
                                            <span>1</span>
                                        </template>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black" :class="activeStep === 1 ? 'text-primary' : 'text-slate-700'">Cập nhật thông tin</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Bước 1</p>
                                    </div>
                                </div>
                                <div class="hidden md:block flex-grow h-0.5 bg-slate-200 mx-2" :class="activeStep > 1 ? 'bg-emerald-500' : 'bg-slate-200'"></div>

                                <!-- Step 2 Indicator -->
                                <div class="flex items-center gap-3 cursor-pointer" @click="activeStep = 2">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs transition-all duration-300"
                                         :class="activeStep === 2 ? 'bg-primary text-white ring-4 ring-primary/20' : (activeStep > 2 ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-500')">
                                        <template x-if="activeStep > 2">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </template>
                                        <template x-if="activeStep <= 2">
                                            <span>2</span>
                                        </template>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black" :class="activeStep === 2 ? 'text-primary' : 'text-slate-700'">Đổi mật khẩu</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Bước 2</p>
                                    </div>
                                </div>
                                <div class="hidden md:block flex-grow h-0.5 bg-slate-200 mx-2" :class="activeStep > 2 ? 'bg-emerald-500' : 'bg-slate-200'"></div>

                                <!-- Step 3 Indicator -->
                                <div class="flex items-center gap-3 cursor-pointer" @click="activeStep = 3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs transition-all duration-300"
                                         :class="activeStep === 3 ? 'bg-primary text-white ring-4 ring-primary/20' : (activeStep > 3 ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-500')">
                                        <template x-if="activeStep > 3">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </template>
                                        <template x-if="activeStep <= 3">
                                            <span>3</span>
                                        </template>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black" :class="activeStep === 3 ? 'text-primary' : 'text-slate-700'">Đổi Avatar</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Bước 3</p>
                                    </div>
                                </div>
                                <div class="hidden md:block flex-grow h-0.5 bg-slate-200 mx-2" :class="activeStep > 3 ? 'bg-emerald-500' : 'bg-slate-200'"></div>

                                <!-- Step 4 Indicator -->
                                <div class="flex items-center gap-3 cursor-pointer" @click="cccdNumberInput = idNumberInput; cccdDateInput = idDateInput; cccdPlaceInput = idPlaceInput; activeStep = 4">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs transition-all duration-300"
                                         :class="activeStep === 4 ? 'bg-primary text-white ring-4 ring-primary/20' : (user && idNumberInput ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-500')">
                                        <span>4</span>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black" :class="activeStep === 4 ? 'text-primary' : 'text-slate-700'">Xác thực CCCD</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Bước 4</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 1: Cập nhật thông tin -->
                        <div x-show="activeStep === 1"
                             x-transition:enter="transition ease-out duration-300 transform"
                             x-transition:enter-start="opacity-0 translate-y-4"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="space-y-6">
                            <div>
                                <h3 class="text-lg font-black text-slate-800">Cập nhật thông tin cá nhân</h3>
                                <p class="text-xs text-slate-400 font-medium">Cập nhật các thông tin cơ bản của tài khoản (không bao gồm mật khẩu và CCCD)</p>
                            </div>
                            <form @submit.prevent="updateProfile();" class="space-y-6 max-w-2xl">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Họ</label>
                                        <input type="text" x-model="firstnameInput" placeholder="Ví dụ: Nguyễn" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3.5 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tên</label>
                                        <input type="text" x-model="lastnameInput" placeholder="Ví dụ: Văn A" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3.5 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700">
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Số điện thoại</label>
                                        <input type="tel" x-model="phoneInput" placeholder="Số điện thoại" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3.5 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Website cá nhân</label>
                                        <input type="url" x-model="websiteInput" placeholder="https://example.com" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3.5 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Giới tính</label>
                                        <select x-model="genderInput" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3.5 text-sm focus:outline-none focus:border-primary focus:bg-white text-slate-700">
                                            <option value="0">Nam</option>
                                            <option value="1">Nữ</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tỉnh / Thành phố</label>
                                        <select x-model="provinceInput" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3.5 text-sm focus:outline-none focus:border-primary focus:bg-white text-slate-700">
                                            <option value="">Chọn Tỉnh / Thành phố</option>
                                            <template x-for="p in provinces" :key="p.id || p.name">
                                                <option :value="p.id || p.name" x-text="p.name" :selected="provinceInput == p.id || provinceInput == p.name"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Ngày sinh</label>
                                        <input type="date" x-model="dobInput" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3.5 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nơi sinh</label>
                                        <input type="text" x-model="pobInput" placeholder="Ví dụ: Hà Nội" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3.5 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tiểu sử / Giới thiệu bản thân</label>
                                    <textarea rows="3" x-model="introInput" placeholder="Giới thiệu ngắn gọn về bản thân..." class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3.5 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700"></textarea>
                                </div>
                                
                                <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                                    <button type="submit" class="bg-primary text-white font-bold px-8 py-3.5 rounded-full text-xs shadow-md btn-hover-premium transition-all hover:scale-[1.02]">
                                        Cập nhật & Lưu thông tin
                                    </button>
                                    <button type="button" @click="activeStep = 2" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-6 py-3.5 rounded-full text-xs transition-colors flex items-center gap-1">
                                        Tiếp theo
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Step 2: Đổi mật khẩu -->
                        <div x-show="activeStep === 2"
                             x-transition:enter="transition ease-out duration-300 transform"
                             x-transition:enter-start="opacity-0 translate-y-4"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-cloak class="space-y-6 max-w-2xl">
                            <div>
                                <h3 class="text-lg font-black text-slate-800">Đổi mật khẩu tài khoản</h3>
                                <p class="text-xs text-slate-400 font-medium">Cập nhật mật khẩu bảo mật tài khoản thành viên NKS</p>
                            </div>
                            
                            <form @submit.prevent="updatePassword" class="space-y-4">
                                <!-- Old Password -->
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Mật khẩu hiện tại</label>
                                    <input type="password" 
                                           x-model="passwordCurrent" 
                                           placeholder="••••••••" 
                                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700 font-semibold">
                                </div>

                                <!-- New Password -->
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Mật khẩu mới</label>
                                        <!-- Collapsible Generator trigger -->
                                        <button type="button" 
                                                @click="showPasswordGenerator = !showPasswordGenerator"
                                                class="text-[10px] text-primary hover:text-primary-dark font-extrabold flex items-center gap-1 transition-colors uppercase tracking-wider">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                            </svg>
                                            <span x-text="showPasswordGenerator ? 'Đóng bộ tạo' : 'Tạo ngẫu nhiên'"></span>
                                        </button>
                                    </div>
                                    <input type="password" 
                                           x-model="passwordNew" 
                                           placeholder="••••••••" 
                                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700 font-semibold">
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Xác nhận mật khẩu mới</label>
                                    <input type="password" 
                                           x-model="passwordConfirm" 
                                           placeholder="••••••••" 
                                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700 font-semibold">
                                </div>

                                <!-- Collapsible Random Password Generator Card -->
                                <div x-show="showPasswordGenerator" 
                                     x-collapse 
                                     x-cloak 
                                     class="bg-slate-50 rounded-2xl p-4 border border-slate-200/60 space-y-4">
                                    <div class="flex items-center justify-between border-b border-slate-200/60 pb-2">
                                        <span class="text-xs font-extrabold text-slate-700 flex items-center gap-1">
                                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                            Tạo mật khẩu an toàn
                                        </span>
                                    </div>

                                    <!-- Length Slider -->
                                    <div class="space-y-1">
                                        <div class="flex justify-between items-center text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                            <span>Độ dài mật khẩu</span>
                                            <span class="text-primary" x-text="genLength + ' ký tự'"></span>
                                        </div>
                                        <input type="range" 
                                               min="6" 
                                               max="32" 
                                               step="1" 
                                               x-model="genLength" 
                                               class="w-full h-1.5 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-primary">
                                    </div>

                                    <!-- Checkboxes Grid -->
                                    <div class="grid grid-cols-2 gap-2.5">
                                        <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-600">
                                            <input type="checkbox" x-model="genUpper" class="rounded border-slate-300 text-primary focus:ring-primary w-4 h-4">
                                            Chữ HOA
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-600">
                                            <input type="checkbox" x-model="genLower" class="rounded border-slate-300 text-primary focus:ring-primary w-4 h-4">
                                            Chữ thường
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-600">
                                            <input type="checkbox" x-model="genNumbers" class="rounded border-slate-300 text-primary focus:ring-primary w-4 h-4">
                                            Chữ số (0-9)
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-600">
                                            <input type="checkbox" x-model="genSpecial" class="rounded border-slate-300 text-primary focus:ring-primary w-4 h-4">
                                            Ký tự đặc biệt
                                        </label>
                                    </div>

                                    <!-- Generate button -->
                                    <button type="button" 
                                            @click="generateRandomPassword" 
                                            class="w-full bg-slate-200 hover:bg-slate-300/80 text-slate-700 font-extrabold py-2 rounded-xl text-xs transition-colors">
                                        Tự động sinh mật khẩu
                                    </button>

                                    <!-- Generated password display and copy -->
                                    <div x-show="generatedPass" class="space-y-3 pt-2 border-t border-slate-200/60" x-cloak>
                                        <div class="relative">
                                            <input type="text" 
                                                   readonly 
                                                   :value="generatedPass" 
                                                   class="w-full bg-white border border-slate-200 rounded-xl pl-3 pr-10 py-2.5 text-xs text-slate-700 font-mono font-bold focus:outline-none select-all">
                                            <button type="button" 
                                                    @click="navigator.clipboard.writeText(generatedPass); alert('Đã sao chép mật khẩu mới vào bộ nhớ tạm!');" 
                                                    class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary transition-colors"
                                                    title="Sao chép">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- "Tôi đã lưu mật khẩu này" checkbox -->
                                        <label class="flex items-start gap-2.5 cursor-pointer text-[11px] text-amber-700 font-extrabold leading-relaxed">
                                            <input type="checkbox" x-model="hasSavedGenPass" class="rounded border-amber-300 text-amber-600 focus:ring-amber-500 w-4 h-4 mt-0.5">
                                            <span>Tôi đã lưu mật khẩu mới này vào nơi an toàn (ghi chú, quản lý mật khẩu...)</span>
                                        </label>

                                        <!-- Apply button -->
                                        <button type="button" 
                                                @click="applyGeneratedPassword" 
                                                :disabled="!hasSavedGenPass"
                                                class="w-full bg-primary hover:bg-primary-dark disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold py-2 rounded-xl text-xs transition-all shadow-md">
                                            Áp dụng & Điền tự động
                                        </button>
                                    </div>
                                </div>

                                <!-- Action buttons -->
                                <div class="flex gap-3 pt-4 border-t border-slate-100">
                                    <button type="button" @click="activeStep = 1" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-6 py-3.5 rounded-full text-xs transition-colors flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                        </svg>
                                        Quay lại
                                    </button>
                                    <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-bold px-8 py-3.5 rounded-full text-xs transition-all shadow-md">
                                        Cập nhật mật khẩu
                                    </button>
                                    <button type="button" @click="activeStep = 3" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-6 py-3.5 rounded-full text-xs transition-colors flex items-center gap-1 ml-auto">
                                        Tiếp theo
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Step 3: Đổi Avatar -->
                        <div x-show="activeStep === 3"
                             x-transition:enter="transition ease-out duration-300 transform"
                             x-transition:enter-start="opacity-0 translate-y-4"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-cloak class="space-y-6 max-w-2xl">
                            <div>
                                <h3 class="text-lg font-black text-slate-800">Cập nhật ảnh đại diện</h3>
                                <p class="text-xs text-slate-400 font-medium">Tải lên, thu phóng và xoay ảnh đại diện của bạn</p>
                            </div>

                            <!-- Avatar Preview Area -->
                            <div class="flex flex-col items-center justify-center space-y-4 py-6 bg-slate-50 rounded-3xl border border-dashed border-slate-200 relative">
                                <template x-if="!avatarImgSrc">
                                    <label class="cursor-pointer flex flex-col items-center justify-center p-6 text-center space-y-2 w-full">
                                        <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center text-primary transition-all hover:scale-105">
                                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <span class="text-xs font-bold text-slate-600">Nhấp để chọn file ảnh</span>
                                        <span class="text-[10px] text-slate-400 font-medium">Hỗ trợ JPG, PNG, GIF</span>
                                        <input type="file" class="hidden" accept="image/*" @change="handleAvatarSelect">
                                    </label>
                                </template>

                                <template x-if="avatarImgSrc">
                                    <div class="w-full flex flex-col items-center space-y-6">
                                        <!-- Circular Mask container -->
                                        <div class="w-44 h-44 rounded-full overflow-hidden border-4 border-white shadow-xl relative bg-slate-100 flex items-center justify-center">
                                            <img :src="avatarImgSrc" 
                                                 alt="Crop Preview" 
                                                 class="w-full h-full object-cover transition-transform duration-75"
                                                 :style="`transform: scale(${avatarZoom}) rotate(${avatarRotate}deg);`" />
                                        </div>

                                        <!-- Controls -->
                                        <div class="w-full px-6 space-y-4 max-w-sm">
                                            <!-- Zoom Slider -->
                                            <div class="space-y-1">
                                                <div class="flex justify-between items-center text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                                    <span>Thu phóng</span>
                                                    <span x-text="Math.round(avatarZoom * 100) + '%'"></span>
                                                </div>
                                                <input type="range" 
                                                       min="1" 
                                                       max="3" 
                                                       step="0.05" 
                                                       x-model="avatarZoom" 
                                                       class="w-full h-1.5 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-primary">
                                            </div>

                                            <!-- Rotation Slider -->
                                            <div class="space-y-1">
                                                <div class="flex justify-between items-center text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                                    <span>Xoay ảnh</span>
                                                    <span x-text="avatarRotate + '°'"></span>
                                                </div>
                                                <input type="range" 
                                                       min="0" 
                                                       max="360" 
                                                       step="1" 
                                                       x-model="avatarRotate" 
                                                       class="w-full h-1.5 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-primary">
                                            </div>
                                        </div>

                                        <!-- Retake button -->
                                        <label class="cursor-pointer text-xs font-bold text-primary hover:text-primary-dark transition-colors flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89M9 11l3-3 3 3m-3-3v12" />
                                            </svg>
                                            Chọn ảnh khác
                                            <input type="file" class="hidden" accept="image/*" @change="handleAvatarSelect">
                                        </label>
                                    </div>
                                </template>
                            </div>

                            <!-- Action buttons -->
                            <div class="flex gap-3 pt-4 border-t border-slate-100">
                                <button type="button" @click="activeStep = 2" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-6 py-3.5 rounded-full text-xs transition-colors flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                    </svg>
                                    Quay lại
                                </button>
                                <button @click="saveAvatar" :disabled="!avatarImgSrc" class="bg-primary hover:bg-primary-dark disabled:opacity-50 text-white font-bold px-8 py-3.5 rounded-full text-xs transition-all shadow-md">
                                    Lưu ảnh đại diện
                                </button>
                                <button type="button" @click="cccdNumberInput = idNumberInput; cccdDateInput = idDateInput; cccdPlaceInput = idPlaceInput; activeStep = 4" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-6 py-3.5 rounded-full text-xs transition-colors flex items-center gap-1 ml-auto">
                                    Tiếp theo
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Step 4: Xác thực CCCD -->
                        <div x-show="activeStep === 4"
                             x-transition:enter="transition ease-out duration-300 transform"
                             x-transition:enter-start="opacity-0 translate-y-4"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-cloak class="space-y-6 max-w-2xl">
                            <div>
                                <h3 class="text-lg font-black text-slate-800">Xác thực CCCD / CMND</h3>
                                <p class="text-xs text-slate-400 font-medium">Tải lên hình ảnh CCCD 2 mặt và cập nhật thông tin giấy tờ</p>
                            </div>

                            <!-- Info banner -->
                            <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 flex gap-3 text-xs text-amber-700 leading-relaxed">
                                <svg class="w-5 h-5 flex-shrink-0 text-amber-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div>
                                    <p class="font-extrabold mb-0.5">Yêu cầu hình ảnh:</p>
                                    <p>Ảnh chụp rõ nét, không bị lóa sáng, không mất góc và không bị che khuất các thông tin cá nhân quan trọng.</p>
                                </div>
                            </div>

                            <!-- Image Selectors side-by-side -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Front Side Card -->
                                <div class="space-y-2">
                                    <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Mặt trước CCCD</span>
                                    <div class="h-40 border-2 border-dashed border-slate-200 hover:border-primary/50 bg-slate-50/50 rounded-2xl overflow-hidden relative flex flex-col items-center justify-center p-4 transition-all group cursor-pointer">
                                        <template x-if="!cccdFrontSrc">
                                            <label class="w-full h-full flex flex-col items-center justify-center cursor-pointer text-center space-y-1.5">
                                                <svg class="w-7 h-7 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                </svg>
                                                <span class="text-xs font-bold text-slate-600">Chọn ảnh mặt trước</span>
                                                <span class="text-[9px] text-slate-400 font-medium">Nhấp để tải lên</span>
                                                <input type="file" class="hidden" accept="image/*" @change="handleCccdSelect($event, 'front')">
                                            </label>
                                        </template>
                                        <template x-if="cccdFrontSrc">
                                            <div class="w-full h-full relative group">
                                                <img :src="cccdFrontSrc" alt="CCCD Front" class="w-full h-full object-cover rounded-xl">
                                                <div class="absolute inset-0 bg-slate-900/50 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all duration-200 rounded-xl">
                                                    <label class="cursor-pointer bg-white text-slate-800 text-[10px] font-bold px-3 py-1.5 rounded-xl shadow-md hover:scale-105 transition-all">
                                                        Thay đổi
                                                        <input type="file" class="hidden" accept="image/*" @change="handleCccdSelect($event, 'front')">
                                                    </label>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <!-- Back Side Card -->
                                <div class="space-y-2">
                                    <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Mặt sau CCCD</span>
                                    <div class="h-40 border-2 border-dashed border-slate-200 hover:border-primary/50 bg-slate-50/50 rounded-2xl overflow-hidden relative flex flex-col items-center justify-center p-4 transition-all group cursor-pointer">
                                        <template x-if="!cccdBackSrc">
                                            <label class="w-full h-full flex flex-col items-center justify-center cursor-pointer text-center space-y-1.5">
                                                <svg class="w-7 h-7 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                </svg>
                                                <span class="text-xs font-bold text-slate-600">Chọn ảnh mặt sau</span>
                                                <span class="text-[9px] text-slate-400 font-medium">Nhấp để tải lên</span>
                                                <input type="file" class="hidden" accept="image/*" @change="handleCccdSelect($event, 'back')">
                                            </label>
                                        </template>
                                        <template x-if="cccdBackSrc">
                                            <div class="w-full h-full relative group">
                                                <img :src="cccdBackSrc" alt="CCCD Back" class="w-full h-full object-cover rounded-xl">
                                                <div class="absolute inset-0 bg-slate-900/50 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all duration-200 rounded-xl">
                                                    <label class="cursor-pointer bg-white text-slate-800 text-[10px] font-bold px-3 py-1.5 rounded-xl shadow-md hover:scale-105 transition-all">
                                                        Thay đổi
                                                        <input type="file" class="hidden" accept="image/*" @change="handleCccdSelect($event, 'back')">
                                                    </label>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- Input Fields Grid -->
                            <div class="space-y-4 pt-2 border-t border-slate-100">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Số CCCD / CMND (12 số)</label>
                                    <input type="text" 
                                           x-model="cccdNumberInput" 
                                           maxlength="12"
                                           placeholder="Ví dụ: 012345678901" 
                                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700 font-semibold">
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Ngày cấp</label>
                                        <input type="date" 
                                               x-model="cccdDateInput" 
                                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Nơi cấp</label>
                                        <input type="text" 
                                               x-model="cccdPlaceInput" 
                                               placeholder="Ví dụ: Cục Cảnh sát QLHC về TTXH" 
                                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700">
                                    </div>
                                </div>
                            </div>

                            <!-- Read-only Display of current info if present -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-slate-50/50 p-4 rounded-3xl border border-slate-100">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Số CCCD đã lưu</label>
                                    <div class="text-xs font-semibold text-slate-600 py-1" x-text="idNumberInput || 'Chưa cập nhật'"></div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Ngày cấp</label>
                                    <div class="text-xs font-semibold text-slate-600 py-1" x-text="idDateInput || 'Chưa cập nhật'"></div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nơi cấp</label>
                                    <div class="text-xs font-semibold text-slate-600 py-1" x-text="idPlaceInput || 'Chưa cập nhật'"></div>
                                </div>
                            </div>

                            <!-- Action buttons -->
                            <div class="flex gap-3 pt-4 border-t border-slate-100">
                                <button type="button" @click="activeStep = 3" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-6 py-3.5 rounded-full text-xs transition-colors flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                    </svg>
                                    Quay lại
                                </button>
                                <button @click="saveCccd" :disabled="!cccdFrontSrc || !cccdBackSrc || !cccdNumberInput" class="bg-primary hover:bg-primary-dark disabled:opacity-50 text-white font-bold px-8 py-3.5 rounded-full text-xs transition-all shadow-md">
                                    Xác thực & Lưu
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- TAB: FAVORITES -->
                    <div x-show="activeTab === 'favorites' && isLoggedIn"
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200 transform"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-4"
                         x-cloak class="space-y-8">
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
                                <a href="/properties" class="inline-flex bg-primary text-white font-bold px-6 py-2.5 rounded-full text-sm shadow-md btn-hover-premium">Khám phá ngay</a>
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
                    <div x-show="activeTab === 'appointments' && isLoggedIn"
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200 transform"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-4"
                         x-cloak class="space-y-8">
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
                                <a href="/properties" class="inline-flex bg-primary text-white font-bold px-6 py-2.5 rounded-full text-sm shadow-md btn-hover-premium">Đặt lịch ngay</a>
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
                    <div x-show="activeTab === 'properties' && isLoggedIn && user && user.role === 'owner'"
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200 transform"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-4"
                         x-cloak class="space-y-8">
                        <div>
                            <h2 class="text-2xl font-extrabold text-slate-800">Tin đăng chính chủ</h2>
                            <p class="text-sm text-slate-400 mt-1">Danh sách bất động sản bạn đang đăng cho thuê trực tiếp</p>
                        </div>
                        
                        <div class="bg-primary-extralight border border-primary/20 rounded-3xl p-6 flex flex-col sm:flex-row justify-between items-center gap-6">
                            <div>
                                <h3 class="font-bold text-primary-dark">Đăng tin cho thuê chính chủ miễn phí</h3>
                                <p class="text-xs text-slate-500 mt-1">Chỉ mất 2 phút để tin đăng của bạn hiển thị tiếp cận hàng ngàn khách thuê có nhu cầu thực tế.</p>
                            </div>
                            <button @click="showAddPropertyModal = true" class="bg-primary text-white font-bold text-xs px-6 py-3 rounded-full shadow-md btn-hover-premium">Đăng tin mới +</button>
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
                                                <div class="flex items-center gap-2">
                                                    <a :href="'/properties/' + item.slug" class="text-xs font-bold text-slate-500 hover:text-primary transition-colors mr-1">Chi tiết</a>
                                                    <!-- Edit Button -->
                                                    <button @click="openEditModal(item)" class="w-7.5 h-7.5 rounded-full bg-white text-slate-500 hover:bg-blue-50 hover:text-blue-600 flex items-center justify-center border border-slate-200/60 shadow-xs transition-all duration-200 active:scale-95" title="Sửa tin đăng">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </button>
                                                    <!-- Delete Button -->
                                                    <button @click="deleteProperty(item.id)" class="w-7.5 h-7.5 rounded-full bg-white text-slate-500 hover:bg-red-50 hover:text-red-600 flex items-center justify-center border border-slate-200/60 shadow-xs transition-all duration-200 active:scale-95" title="Xóa tin đăng">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>

                    <!-- TAB: USER MANAGEMENT (Admin Only) -->
                    <div x-show="activeTab === 'users' && isLoggedIn && user && user.role === 'admin'"
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200 transform"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-4"
                         x-cloak class="space-y-8">
                         
                         <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                             <div>
                                 <h2 class="text-2xl font-extrabold text-slate-800">Quản lý thành viên</h2>
                                 <p class="text-sm text-slate-400 mt-1">Danh sách thành viên đăng ký và phân quyền hệ thống</p>
                             </div>
                             
                             <div class="flex items-center gap-3">
                                 <!-- Add user button -->
                                 <button @click="openAddUserModal()" class="bg-primary text-white font-bold text-xs px-4 py-2.5 rounded-full shadow-md btn-hover-premium flex items-center gap-1 whitespace-nowrap">
                                     <span>Thêm mới +</span>
                                 </button>

                                 <!-- Search bar -->
                                 <div class="relative max-w-xs w-full">
                                     <input type="text" 
                                            x-model="userSearchQuery" 
                                            placeholder="Tìm tên, email, sđt..." 
                                            class="w-full bg-slate-50 border border-slate-200 rounded-full pl-10 pr-4 py-2.5 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700">
                                     <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                                         <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                             <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                         </svg>
                                     </div>
                                 </div>
                             </div>
                         </div>

                         <!-- Loading State -->
                         <div x-show="isLoadingUsers" class="text-center py-12 space-y-3">
                             <div class="inline-block w-8 h-8 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
                             <p class="text-xs text-slate-400">Đang tải danh sách thành viên...</p>
                         </div>

                         <!-- Users Table -->
                         <div x-show="!isLoadingUsers" class="bg-white border border-slate-100 rounded-3xl overflow-hidden shadow-sm">
                             <div class="overflow-x-auto">
                                 <table class="min-w-full divide-y divide-slate-100">
                                     <thead>
                                         <tr class="text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider bg-slate-50/50">
                                             <th class="px-6 py-4">Thành viên</th>
                                             <th class="px-6 py-4">Số điện thoại</th>
                                             <th class="px-6 py-4">Vai trò</th>
                                             <th class="px-6 py-4">Trạng thái</th>
                                             <th class="px-6 py-4">Ngày tham gia</th>
                                             <th class="px-6 py-4 text-right">Thao tác</th>
                                         </tr>
                                     </thead>
                                     <tbody class="divide-y divide-slate-50 text-sm">
                                         <template x-for="u in filteredUsers" :key="u.id">
                                             <tr class="hover:bg-slate-50/30 transition-colors">
                                                 <td class="px-6 py-4">
                                                     <div class="flex items-center gap-3">
                                                         <div class="w-10 h-10 rounded-full overflow-hidden bg-slate-50 border border-slate-100 flex-shrink-0">
                                                             <img :src="u.avatar ? u.avatar : 'https://api.dicebear.com/7.x/adventurer/svg?seed=' + u.name" alt="Avatar" class="w-full h-full object-cover">
                                                         </div>
                                                         <div>
                                                             <div class="font-bold text-slate-800" x-text="u.name"></div>
                                                             <div class="text-xs text-slate-400" x-text="u.email"></div>
                                                         </div>
                                                     </div>
                                                 </td>
                                                 <td class="px-6 py-4 text-slate-600 font-semibold" x-text="u.phone || 'Chưa cập nhật'"></td>
                                                 <td class="px-6 py-4">
                                                     <template x-if="u.role === 'admin'">
                                                         <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-extrabold tracking-wider uppercase bg-rose-50 text-rose-600 border border-rose-100">Admin</span>
                                                     </template>
                                                     <template x-if="u.role === 'owner'">
                                                         <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-extrabold tracking-wider uppercase bg-emerald-50 text-emerald-600 border border-emerald-100">Chủ nhà</span>
                                                     </template>
                                                     <template x-if="u.role === 'renter'">
                                                         <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-extrabold tracking-wider uppercase bg-blue-50 text-blue-600 border border-blue-100">Khách thuê</span>
                                                     </template>
                                                 </td>
                                                 <td class="px-6 py-4">
                                                     <template x-if="u.status === 'blocked'">
                                                         <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-extrabold tracking-wider uppercase bg-red-50 text-red-600 border border-red-100">Khóa</span>
                                                     </template>
                                                     <template x-if="u.status !== 'blocked'">
                                                         <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-extrabold tracking-wider uppercase bg-green-50 text-green-600 border border-green-100">Hoạt động</span>
                                                     </template>
                                                 </td>
                                                 <td class="px-6 py-4 text-xs text-slate-500" x-text="new Date(u.created_at).toLocaleDateString('vi-VN')"></td>
                                                 <td class="px-6 py-4">
                                                     <div class="flex items-center justify-end gap-2">
                                                         <button @click="viewUserDetails(u)" class="w-8 h-8 rounded-full bg-white text-slate-500 hover:bg-slate-50 hover:text-slate-700 flex items-center justify-center border border-slate-200/60 shadow-xs transition-all active:scale-95" title="Chi tiết hoạt động">
                                                             <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                         </button>
                                                         <button @click="toggleUserStatus(u.id)" 
                                                                 :disabled="parseInt(u.id) === parseInt(user.id)"
                                                                 :class="parseInt(u.id) === parseInt(user.id) ? 'opacity-35 cursor-not-allowed' : (u.status === 'blocked' ? 'hover:bg-green-50 hover:text-green-600 text-slate-400' : 'hover:bg-red-50 hover:text-red-600 text-slate-400')"
                                                                 class="w-8 h-8 rounded-full bg-white flex items-center justify-center border border-slate-200/60 shadow-xs transition-all active:scale-95" :title="u.status === 'blocked' ? 'Kích hoạt tài khoản' : 'Khóa tài khoản'">
                                                             <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                 <path x-show="u.status !== 'blocked'" stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                                 <path x-show="u.status === 'blocked'" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                             </svg>
                                                         </button>
                                                         <button @click="deleteUser(u.id)" 
                                                                 :disabled="parseInt(u.id) === parseInt(user.id)"
                                                                 :class="parseInt(u.id) === parseInt(user.id) ? 'opacity-35 cursor-not-allowed' : 'hover:bg-red-50 hover:text-red-600'"
                                                                 class="w-8 h-8 rounded-full bg-white text-slate-500 flex items-center justify-center border border-slate-200/60 shadow-xs transition-all active:scale-95" title="Xóa thành viên">
                                                             <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                         </button>
                                                     </div>
                                                 </td>
                                             </tr>
                                         </template>
                                         <template x-if="filteredUsers.length === 0">
                                             <tr>
                                                 <td colspan="6" class="px-6 py-12 text-center text-slate-400 text-xs">Không tìm thấy thành viên phù hợp.</td>
                                             </tr>
                                         </template>
                                     </tbody>
                                 </table>
                             </div>
                         </div>
                    </div>
                    
                    <!-- TAB: REGISTER AS OWNER / HOST -->
                    <div x-show="activeTab === 'host'"
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200 transform"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-4"
                         x-cloak class="space-y-8">
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
                                
                                <button type="submit" class="w-full bg-primary text-white font-bold px-6 py-4 rounded-full text-sm shadow-md btn-hover-premium">Đăng ký làm chủ nhà ngay</button>
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
                    <div x-show="activeTab === 'login'"
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200 transform"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-4"
                         x-cloak class="space-y-8 max-w-md mx-auto">
                        <div class="text-center">
                            <h2 class="text-2xl font-extrabold text-slate-800">Đăng nhập tài khoản</h2>
                            <p class="text-sm text-slate-400 mt-1">Đăng nhập để lưu tin yêu thích và quản lý lịch hẹn</p>
                        </div>
                        
                        <!-- Normal Login Form (First Time) -->
                        <form x-show="!showSecondLogin" @submit.prevent="login($refs.loginEmail.value, $refs.loginPass.value)" class="space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tên đăng nhập hoặc Email</label>
                                <input type="text" x-ref="loginEmail" placeholder="Nhập tên đăng nhập hoặc email" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3.5 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Mật khẩu</label>
                                <input type="password" x-ref="loginPass" placeholder="••••••••" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3.5 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all">
                            </div>
                            <button type="submit" class="w-full bg-primary text-white font-bold py-4 rounded-full text-sm shadow-md btn-hover-premium">Đăng nhập</button>
                        </form>

                        <!-- Second Login Form (Second Access) -->
                        <form x-show="showSecondLogin" @submit.prevent="login(lastUsername, $refs.loginPassSecond.value)" class="space-y-5" style="display: none;" x-cloak>
                            <div class="bg-slate-50 border border-slate-200/60 rounded-3xl p-5 text-center relative overflow-hidden">
                                <div class="w-16 h-16 rounded-full bg-primary/10 overflow-hidden mx-auto mb-2 border border-slate-100 flex items-center justify-center">
                                    <img :src="'https://api.dicebear.com/7.x/adventurer/svg?seed=' + encodeURIComponent(lastUsername)" alt="Avatar" class="w-full h-full object-cover">
                                </div>
                                <h3 class="text-sm font-bold text-slate-700 truncate" x-text="lastUsername"></h3>
                                <p class="text-xs text-slate-400 mt-0.5">Chào mừng bạn quay lại!</p>
                                
                                <button type="button" @click="showSecondLogin = false" class="absolute top-3 right-3 text-slate-400 hover:text-primary transition-all p-1.5 hover:bg-white rounded-full shadow-xs" title="Đăng nhập bằng tài khoản khác">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                </button>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Mật khẩu</label>
                                <input type="password" x-ref="loginPassSecond" placeholder="••••••••" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3.5 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all">
                            </div>
                            <button type="submit" class="w-full bg-primary text-white font-bold py-4 rounded-full text-sm shadow-md btn-hover-premium">Đăng nhập</button>
                            <div class="text-center mt-2">
                                <button type="button" @click="showSecondLogin = false" class="text-xs font-bold text-primary hover:underline flex items-center gap-1 mx-auto bg-transparent border-0 cursor-pointer">
                                    Đăng nhập tài khoản khác &rarr;
                                </button>
                            </div>
                        </form>
                        
                        <div class="text-center text-sm text-slate-400">
                            Chưa có tài khoản? <button @click="activeTab = 'register'" class="text-primary font-bold hover:underline bg-transparent border-0 cursor-pointer">Đăng ký thành viên</button>
                        </div>
                    </div>
                    
                    <!-- TAB: REGISTER -->
                    <div x-show="activeTab === 'register'"
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200 transform"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-4"
                         x-cloak class="space-y-8 max-w-md mx-auto">
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
                                <div class="relative">
                                    <input :type="showRegisterPassword ? 'text' : 'password'" x-ref="regPass" required placeholder="Mật khẩu ít nhất 6 ký tự" class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-4 pr-12 py-3.5 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all">
                                    <button type="button" @click="showRegisterPassword = !showRegisterPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors focus:outline-none">
                                        <svg x-show="!showRegisterPassword" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="showRegisterPassword" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;" x-cloak>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nhập lại mật khẩu</label>
                                <div class="relative">
                                    <input :type="showRegisterPassword ? 'text' : 'password'" x-ref="regPassConfirm" required placeholder="Nhập lại mật khẩu" class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-4 pr-12 py-3.5 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all">
                                    <button type="button" @click="showRegisterPassword = !showRegisterPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors focus:outline-none">
                                        <svg x-show="!showRegisterPassword" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="showRegisterPassword" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;" x-cloak>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Vai trò tài khoản</label>
                                <select x-ref="regRole" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3.5 text-sm focus:outline-none focus:border-primary focus:bg-white transition-all">
                                    <option value="renter">Tôi là Khách thuê tìm nhà</option>
                                    <option value="owner">Tôi là Chủ nhà cho thuê</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="w-full bg-primary text-white font-bold py-4 rounded-full text-sm shadow-md btn-hover-premium">Đăng ký thành viên</button>
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


    <!-- EDIT PROPERTY MODAL OVERLAY -->
    <div x-show="showEditPropertyModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         style="display: none;"
         x-cloak>
        
        <div @click.away="showEditPropertyModal = false" 
             x-show="showEditPropertyModal"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="bg-white rounded-[32px] shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden border border-slate-100 flex flex-col relative">
            
            <button @click="showEditPropertyModal = false" class="absolute top-4 right-4 z-50 w-9 h-9 rounded-full bg-white/95 hover:bg-white text-slate-500 hover:text-slate-800 shadow-md flex items-center justify-center transition-all active:scale-95 border border-slate-100">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>

            <div class="p-6 sm:p-8 overflow-y-auto space-y-6">
                <div>
                    <h2 class="text-xl font-black text-slate-900 leading-snug">Chỉnh sửa tin đăng</h2>
                    <p class="text-xs text-slate-400">Cập nhật thông số chính xác để xác thực 3 Thật và lưu trực tuyến.</p>
                </div>

                <form @submit.prevent="updateProperty()" class="space-y-4">
                    <!-- Title -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Tiêu đề tin đăng *</label>
                        <input type="text" x-model="editPropTitle" required placeholder="Ví dụ: Căn hộ Studio view sông..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700">
                    </div>

                    <!-- Address & GPS -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Địa chỉ chi tiết *</label>
                            <input type="text" x-model="editPropAddress" required placeholder="Ví dụ: 123 Nguyễn Huệ..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Tọa độ GPS (Lat, Lng) *</label>
                            <input type="text" x-model="editPropGeolocation" required placeholder="10.7932,106.6710" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700">
                        </div>
                    </div>

                    <!-- Type, TxType, Price, Area -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Loại hình</label>
                            <select x-model="editPropType" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white text-slate-700">
                                <option value="Căn hộ">Căn hộ</option>
                                <option value="Nhà phố">Nhà phố</option>
                                <option value="Biệt thự">Biệt thự</option>
                                <option value="Phòng trọ">Phòng trọ</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Loại tin</label>
                            <select x-model="editPropTxType" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white text-slate-700">
                                <option value="Cho thuê">Cho thuê</option>
                                <option value="Bán">Bán</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Giá (VND) *</label>
                            <input type="number" x-model="editPropPrice" required placeholder="Ví dụ: 12000000" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Diện tích (m²) *</label>
                            <input type="number" step="0.1" x-model="editPropArea" required placeholder="45.0" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700">
                        </div>
                    </div>

                    <!-- Bed, Bath, Floors, Direction -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Phòng ngủ</label>
                            <input type="number" x-model="editPropBed" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white text-slate-700">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Phòng tắm</label>
                            <input type="number" x-model="editPropBath" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white text-slate-700">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Số tầng</label>
                            <input type="number" x-model="editPropFloors" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white text-slate-700">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Hướng nhà</label>
                            <select x-model="editPropDirection" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white text-slate-700">
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
                        <input type="url" x-model="editPropFeatureImg" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700">
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Mô tả bất động sản</label>
                        <textarea rows="3" x-model="editPropDesc" placeholder="Mô tả các tiện ích đi kèm, khu vực lân cận..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-extrabold py-4 rounded-xl shadow-md shadow-primary/20 hover:shadow-lg transition-all hover:scale-[1.01] active:scale-95 text-xs uppercase tracking-wider">
                        Lưu thay đổi
                    </button>
                </form>
            </div>
        </div>
    </div>



    <!-- ADD USER MODAL OVERLAY (Admin Only) -->
    <div x-show="showAddUserModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         style="display: none;"
         x-cloak>
        
        <div @click.away="showAddUserModal = false" 
             x-show="showAddUserModal"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="bg-white rounded-[32px] shadow-2xl max-w-md w-full overflow-hidden border border-slate-100 flex flex-col relative">
            
            <button @click="showAddUserModal = false" class="absolute top-4 right-4 z-50 w-9 h-9 rounded-full bg-white/95 hover:bg-white text-slate-500 hover:text-slate-800 shadow-md flex items-center justify-center transition-all active:scale-95 border border-slate-100">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>

            <div class="p-6 sm:p-8 space-y-6">
                <div>
                    <h2 class="text-xl font-black text-slate-900 leading-snug">Thêm thành viên mới</h2>
                    <p class="text-xs text-slate-400">Khởi tạo tài khoản thành viên hệ thống BDS NKS.</p>
                </div>

                <form @submit.prevent="addUser()" class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Tên hiển thị *</label>
                        <input type="text" x-model="addUserForm.name" required placeholder="Nguyễn Văn A" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Địa chỉ Email *</label>
                        <input type="email" x-model="addUserForm.email" required placeholder="user@nks.vn" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700">
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Mật khẩu khởi tạo *</label>
                        <input type="password" x-model="addUserForm.password" required placeholder="Mật khẩu từ 6 ký tự" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700">
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Số điện thoại</label>
                        <input type="text" x-model="addUserForm.phone" placeholder="09xxxxxxxx" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-all text-slate-700">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Role -->
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Vai trò *</label>
                            <select x-model="addUserForm.role" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white text-slate-700">
                                <option value="renter">Khách thuê</option>
                                <option value="owner">Chủ nhà</option>
                                <option value="admin">Quản trị viên</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Trạng thái *</label>
                            <select x-model="addUserForm.status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-3 text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white text-slate-700">
                                <option value="active">Hoạt động</option>
                                <option value="blocked">Khóa</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-extrabold py-4 rounded-xl shadow-md shadow-primary/20 hover:shadow-lg transition-all hover:scale-[1.01] active:scale-95 text-xs uppercase tracking-wider">
                        Tạo tài khoản
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- USER DETAILS MODAL OVERLAY (Admin Only) -->
    <div x-show="showUserDetailsModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         style="display: none;"
         x-cloak>
        
        <div @click.away="showUserDetailsModal = false" 
             x-show="showUserDetailsModal"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="bg-white rounded-[32px] shadow-2xl max-w-2xl w-full max-h-[85vh] overflow-hidden border border-slate-100 flex flex-col relative">
            
            <button @click="showUserDetailsModal = false" class="absolute top-4 right-4 z-50 w-9 h-9 rounded-full bg-white/95 hover:bg-white text-slate-500 hover:text-slate-800 shadow-md flex items-center justify-center transition-all active:scale-95 border border-slate-100">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>

            <div class="p-6 sm:p-8 overflow-y-auto space-y-6 flex-grow" x-show="selectedUserDetails">
                <div class="flex items-center gap-4 border-b border-slate-100 pb-5">
                    <div class="w-16 h-16 rounded-full overflow-hidden bg-slate-50 border border-slate-100 flex-shrink-0">
                        <img :src="selectedUserDetails.avatar ? selectedUserDetails.avatar : 'https://api.dicebear.com/7.x/adventurer/svg?seed=' + selectedUserDetails.name" alt="Avatar" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-slate-900 leading-snug" x-text="selectedUserDetails.name"></h2>
                        <p class="text-xs text-slate-400" x-text="selectedUserDetails.email"></p>
                        <div class="flex gap-2 mt-1.5">
                            <span class="inline-flex px-2 py-0.5 rounded text-[9px] font-bold uppercase"
                                  :class="selectedUserDetails.role === 'admin' ? 'bg-rose-50 text-rose-600' : (selectedUserDetails.role === 'owner' ? 'bg-emerald-50 text-emerald-600' : 'bg-blue-50 text-blue-600')"
                                  x-text="selectedUserDetails.role === 'admin' ? 'Quản trị' : (selectedUserDetails.role === 'owner' ? 'Chủ nhà' : 'Khách thuê')"></span>
                            <span class="inline-flex px-2 py-0.5 rounded text-[9px] font-bold uppercase"
                                  :class="selectedUserDetails.status === 'blocked' ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600'"
                                  x-text="selectedUserDetails.status === 'blocked' ? 'Bị khóa' : 'Hoạt động'"></span>
                        </div>
                    </div>
                </div>

                <!-- User activity listings -->
                <div class="space-y-6">
                    <!-- Bookings -->
                    <div>
                        <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-2">Lịch hẹn xem nhà (<span x-text="selectedUserDetails.appointments?.length || 0"></span>)</h3>
                        <div class="space-y-2 max-h-[150px] overflow-y-auto pr-1">
                            <template x-for="appt in selectedUserDetails.appointments" :key="appt.id">
                                <div class="p-3 bg-slate-50 rounded-xl text-xs flex justify-between items-center border border-slate-100">
                                    <div>
                                        <p class="font-bold text-slate-700" x-text="appt.appt_name"></p>
                                        <p class="text-slate-400 text-[10px]" x-text="`${appt.appointment_date} lúc ${appt.appointment_time}`"></p>
                                    </div>
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-green-100 text-green-700" x-text="appt.status"></span>
                                </div>
                            </template>
                            <template x-if="!selectedUserDetails.appointments || selectedUserDetails.appointments.length === 0">
                                <p class="text-[10px] text-slate-400 italic">Không có lịch hẹn.</p>
                            </template>
                        </div>
                    </div>

                    <!-- Submitted Properties (Owner only) -->
                    <template x-if="selectedUserDetails.role === 'owner'">
                        <div>
                            <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-2">Tin đăng cho thuê (<span x-text="selectedUserDetails.properties?.length || 0"></span>)</h3>
                            <div class="space-y-2 max-h-[150px] overflow-y-auto pr-1">
                                <template x-for="prop in selectedUserDetails.properties" :key="prop.id">
                                    <div class="p-3 bg-slate-50 rounded-xl text-xs flex justify-between items-center border border-slate-100">
                                        <p class="font-bold text-slate-700 truncate max-w-[80%]" x-text="prop.title"></p>
                                        <span class="font-bold text-primary" x-text="prop.formated_price || prop.price"></span>
                                    </div>
                                </template>
                                <template x-if="!selectedUserDetails.properties || selectedUserDetails.properties.length === 0">
                                    <p class="text-[10px] text-slate-400 italic">Không có tin đăng.</p>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Email logs history for this specific user -->
                    <div>
                        <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-2">Lịch sử Email đã nhận (<span x-text="selectedUserDetails.emails?.length || 0"></span>)</h3>
                        <div class="space-y-2 max-h-[200px] overflow-y-auto pr-1">
                            <template x-for="email in selectedUserDetails.emails" :key="email.id">
                                <div class="p-3 bg-slate-50 hover:bg-slate-100 rounded-xl text-xs border border-slate-100 cursor-pointer" @click="openEmailDetail(email)">
                                    <div class="flex justify-between items-start">
                                        <p class="font-bold text-slate-700" x-text="email.subject"></p>
                                        <span class="text-[9px] text-slate-400" x-text="new Date(email.sent_at || email.created_at).toLocaleDateString('vi-VN')"></span>
                                    </div>
                                    <p class="text-slate-400 text-[10px] truncate mt-1" x-text="email.body"></p>
                                </div>
                            </template>
                            <template x-if="!selectedUserDetails.emails || selectedUserDetails.emails.length === 0">
                                <p class="text-[10px] text-slate-400 italic">Không có email được gửi.</p>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- EMAIL DETAIL MODAL OVERLAY -->
    <div x-show="showEmailDetailModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[120] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         style="display: none;"
         x-cloak>
        
        <div @click.away="showEmailDetailModal = false" 
             x-show="showEmailDetailModal"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="bg-white rounded-[32px] shadow-2xl max-w-lg w-full overflow-hidden border border-slate-100 flex flex-col relative"
             x-show="activeEmailDetail">
            
            <button @click="showEmailDetailModal = false" class="absolute top-4 right-4 z-50 w-9 h-9 rounded-full bg-white/95 hover:bg-white text-slate-500 hover:text-slate-800 shadow-md flex items-center justify-center transition-all active:scale-95 border border-slate-100">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>

            <div class="p-6 sm:p-8 space-y-6" x-show="activeEmailDetail">
                <div class="border-b border-slate-100 pb-4">
                    <span class="inline-flex px-2 py-0.5 rounded text-[9px] font-bold uppercase bg-primary/10 text-primary mb-2">Thư Hệ Thống</span>
                    <h2 class="text-base font-extrabold text-slate-800" x-text="activeEmailDetail.subject"></h2>
                    <div class="mt-2 text-[10px] text-slate-400 space-y-0.5">
                        <p x-text="`Người nhận: ${activeEmailDetail.recipient_email}`"></p>
                        <p x-text="`Thời gian: ${new Date(activeEmailDetail.sent_at || activeEmailDetail.created_at).toLocaleString('vi-VN')}`"></p>
                    </div>
                </div>

                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100/60 max-h-[300px] overflow-y-auto">
                    <p class="text-xs text-slate-600 whitespace-pre-wrap leading-relaxed" x-text="activeEmailDetail.body"></p>
                </div>

                <button @click="showEmailDetailModal = false" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-full text-xs transition-colors">
                    Đóng cửa sổ
                </button>
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
            
            // Admin User Management State
            allUsers: [],
            userSearchQuery: '',
            isLoadingUsers: false,
            
            // New Modals State
            showAddUserModal: false,
            addUserForm: { name: '', email: '', password: '', phone: '', role: 'renter', status: 'active' },
            showUserDetailsModal: false,
            selectedUserDetails: { name: '', email: '', role: '', status: '', appointments: [], properties: [], emails: [] },
            
            // Email Logs State
            emails: [],
            isLoadingEmails: false,
            adminEmails: [],
            isLoadingAdminEmails: false,
            activeEmailDetail: {},
            showEmailDetailModal: false,

            // Support Chat State
            chatMessages: [],
            chatInputMessage: '',
            unreadChatCount: 0,
            chatPollingInterval: null,
            conversations: [],
            adminUnreadChatCount: 0,
            activeChatClient: null,
            adminChatMessages: [],
            adminChatInputMessage: '',
            adminChatPollingInterval: null,
            
            // Edit Profile State & Dynamic Locations
            firstnameInput: '',
            lastnameInput: '',
            introInput: '',
            phoneInput: '',
            genderInput: 0,
            websiteInput: '',
            dobInput: '',
            pobInput: '',
            idNumberInput: '',
            idDateInput: '',
            idPlaceInput: '',
            provinceInput: '',
            point: 0,
            provinces: [],
            activeStep: 1,
            nameInput: '',
            avatarInput: '',
            
            // Edit password & CCCD Modals State
            showUpdatePassModal: false,
            showUpdateCccdModal: false,
            showUpdateAvatarModal: false,
            passwordCurrent: '',
            passwordNew: '',
            passwordConfirm: '',
            showPasswordGenerator: false,
            genLength: 12,
            genUpper: true,
            genLower: true,
            genNumbers: true,
            genSpecial: true,
            generatedPass: '',
            hasSavedGenPass: false,
            cccdNumberInput: '',
            cccdDateInput: '',
            cccdPlaceInput: '',
            
            // Owner Register State
            companyInput: '',
            addressInput: '',
            isOwnerRegSuccess: false,
            showRegisterPassword: false,
            showSecondLogin: false,
            lastUsername: '',
            
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
            
            // Edit Property Form Modal State
            showEditPropertyModal: false,
            editPropId: null,
            editPropTitle: '',
            editPropAddress: '',
            editPropGeolocation: '',
            editPropType: 'Căn hộ',
            editPropTxType: 'Cho thuê',
            editPropPrice: '',
            editPropArea: '',
            editPropBed: 1,
            editPropBath: 1,
            editPropFloors: 1,
            editPropDirection: 'Đông',
            editPropFeatureImg: '',
            editPropDesc: '',
            
            init() {
                const urlParams = new URLSearchParams(window.location.search);
                const tabParam = urlParams.get('tab');
                if (tabParam) {
                    this.activeTab = tabParam;
                }
                
                const savedLastUsername = localStorage.getItem('nks_last_username');
                if (savedLastUsername) {
                    this.lastUsername = savedLastUsername;
                    this.showSecondLogin = true;
                }
                
                this.loadProvinces();
                this.checkLogin();
                
                window.addEventListener('nks-login-change', () => {
                    this.checkLogin();
                });
                
                this.loadMockData();

                // Setup watcher for activeTab changes to handle chat polling and loading logs
                this.$watch('activeTab', (value) => {
                    this.handleTabChange(value);
                });

                // Load initial tab data
                if (tabParam) {
                    this.handleTabChange(tabParam);
                }
            },

            handleTabChange(tab) {
                // Clear any existing polling intervals to avoid leaks
                if (this.chatPollingInterval) {
                    clearInterval(this.chatPollingInterval);
                    this.chatPollingInterval = null;
                }
                if (this.adminChatPollingInterval) {
                    clearInterval(this.adminChatPollingInterval);
                    this.adminChatPollingInterval = null;
                }

                if (tab === 'chat') {
                    this.startChatPolling();
                } else if (tab === 'chat_admin') {
                    this.startAdminChatPolling();
                } else if (tab === 'emails') {
                    this.loadEmails();
                } else if (tab === 'emails_admin') {
                    this.loadAdminEmails();
                } else if (tab === 'users') {
                    this.loadAllUsers();
                }
            },
            
            checkLogin() {
                const savedUser = localStorage.getItem('nks_user');
                if (savedUser) {
                    this.isLoggedIn = true;
                    this.user = JSON.parse(savedUser);
                    
                    this.phoneInput = this.user.phone || '';
                    this.point = this.user.point || 0;
                    
                    if (this.user.firstname) {
                        this.firstnameInput = this.user.firstname;
                        this.lastnameInput = this.user.lastname || '';
                    } else {
                        const parts = (this.user.name || '').split(' ');
                        this.lastnameInput = parts.pop() || '';
                        this.firstnameInput = parts.join(' ') || '';
                    }
                    
                    this.introInput = this.user.intro || '';
                    this.genderInput = this.user.gender !== undefined ? this.user.gender : 0;
                    this.websiteInput = this.user.website || '';
                    this.dobInput = this.user.dob || '';
                    this.pobInput = this.user.pob || '';
                    this.idNumberInput = this.user.id_number || '';
                    this.idDateInput = this.user.id_date || '';
                    this.idPlaceInput = this.user.id_place || '';
                    this.provinceInput = this.user.province || '';
                    
                } else {
                    this.isLoggedIn = false;
                    this.user = null;
                    if (this.activeTab !== 'login' && this.activeTab !== 'register' && this.activeTab !== 'host') {
                        this.activeTab = 'login';
                    }
                }
            },
            
            async loadMockData() {
                if (this.isLoggedIn && this.user && this.user.email) {
                    const savedAppts = localStorage.getItem('nks_appointments');
                    const savedFavs = localStorage.getItem('nks_favorites');
                    const savedProps = localStorage.getItem('nks_owner_properties');

                    try {
                        const res = await fetch('/nks-api/session/sync', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                user: this.user,
                                access_token: localStorage.getItem('nks_access_token') || '',
                                favorites: savedFavs ? JSON.parse(savedFavs) : [],
                                appointments: savedAppts ? JSON.parse(savedAppts) : [],
                                properties: savedProps ? JSON.parse(savedProps) : []
                            })
                        });

                        if (res.ok) {
                            const data = await res.json();
                            this.user = data.user;
                            localStorage.setItem('nks_user', JSON.stringify(this.user));
                            
                            this.appointments = data.appointments || [];
                            localStorage.setItem('nks_appointments', JSON.stringify(this.appointments));

                            this.favorites = data.favorites || [];
                            localStorage.setItem('nks_favorites', JSON.stringify(this.favorites));

                            this.ownerProperties = data.properties || [];
                            localStorage.setItem('nks_owner_properties', JSON.stringify(this.ownerProperties));

                            this.nameInput = this.user.name;
                            this.phoneInput = this.user.phone || '';
                            this.avatarInput = this.user.avatar || '';

                            window.dispatchEvent(new CustomEvent('nks-login-change'));

                            if (this.activeTab === 'users' && this.user && this.user.role === 'admin') {
                                this.loadAllUsers();
                            }
                        } else if (res.status === 401) {
                            this.handleStaleSession();
                            return;
                        }
                    } catch (e) {
                        console.warn('Database sync failed, fallback to local storage:', e);
                    }
                }

                // Fallback to local storage state if offline or request failed
                const savedAppts = localStorage.getItem('nks_appointments');
                if (savedAppts) this.appointments = JSON.parse(savedAppts);
                
                const savedFavs = localStorage.getItem('nks_favorites');
                if (savedFavs) this.favorites = JSON.parse(savedFavs);
                
                const savedOwnerProps = localStorage.getItem('nks_owner_properties');
                if (savedOwnerProps) this.ownerProperties = JSON.parse(savedOwnerProps);
            },
            
            handleStaleSession() {
                localStorage.removeItem('nks_user');
                localStorage.removeItem('nks_appointments');
                localStorage.removeItem('nks_favorites');
                localStorage.removeItem('nks_owner_properties');
                localStorage.removeItem('nks_last_username');
                localStorage.removeItem('nks_access_token');
                this.isLoggedIn = false;
                this.user = null;
                window.dispatchEvent(new CustomEvent('nks-login-change'));
                this.activeTab = 'login';
                alert('Phiên đăng nhập đã hết hạn hoặc tài khoản không tồn tại. Vui lòng đăng nhập lại.');
            },
            
            async addProperty() {
                if (!this.newPropTitle || !this.newPropAddress || !this.newPropPrice || !this.newPropArea) {
                    alert('Vui lòng điền đầy đủ thông tin bắt buộc.');
                    return;
                }

                try {
                    const res = await fetch('/nks-api/properties/add', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
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
                        const err = await res.json();
                        alert(err.message || 'Đăng tin không thành công.');
                    }
                } catch (e) {
                    alert('Lỗi kết nối máy chủ CSDL.');
                }
            },

            openEditModal(item) {
                this.editPropId = item.id;
                this.editPropTitle = item.title;
                this.editPropAddress = item.address;
                this.editPropGeolocation = item.geolocation;
                this.editPropType = item.rstype;
                this.editPropTxType = item.transaction_type;
                this.editPropPrice = item.price;
                this.editPropArea = item.total_area;
                this.editPropBed = item.bed;
                this.editPropBath = item.bath;
                this.editPropFloors = item.floors;
                this.editPropDirection = item.direction || 'Đông';
                this.editPropFeatureImg = item.feature_img || item.featureimg;
                this.editPropDesc = item.description || '';
                this.showEditPropertyModal = true;
            },

            async updateProperty() {
                if (!this.editPropTitle || !this.editPropAddress || !this.editPropPrice || !this.editPropArea) {
                    alert('Vui lòng điền đầy đủ thông tin bắt buộc.');
                    return;
                }

                try {
                    const res = await fetch(`/nks-api/properties/update/${this.editPropId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            user_id: this.user.id,
                            title: this.editPropTitle,
                            address: this.editPropAddress,
                            geolocation: this.editPropGeolocation,
                            rstype: this.editPropType,
                            transaction_type: this.editPropTxType,
                            price: parseFloat(this.editPropPrice),
                            total_area: parseFloat(this.editPropArea),
                            bed: parseInt(this.editPropBed),
                            bath: parseInt(this.editPropBath),
                            floors: parseInt(this.editPropFloors),
                            direction: this.editPropDirection,
                            feature_img: this.editPropFeatureImg,
                            description: this.editPropDesc
                        })
                    });

                    if (res.ok) {
                        const data = await res.json();
                        const idx = this.ownerProperties.findIndex(item => item.id === this.editPropId);
                        if (idx > -1) {
                            this.ownerProperties[idx] = data.property;
                            this.ownerProperties = [...this.ownerProperties];
                        }
                        localStorage.setItem('nks_owner_properties', JSON.stringify(this.ownerProperties));
                        this.showEditPropertyModal = false;
                        alert('Cập nhật tin đăng thành công! Các thay đổi đã được áp dụng.');
                    } else {
                        const err = await res.json();
                        alert(err.message || 'Cập nhật tin đăng không thành công.');
                    }
                } catch (e) {
                    alert('Lỗi kết nối máy chủ CSDL.');
                }
            },

            async deleteProperty(id) {
                if (!confirm('Bạn có chắc chắn muốn xóa tin đăng này? Thao tác này không thể hoàn tác.')) {
                    return;
                }

                try {
                    const res = await fetch(`/nks-api/properties/delete/${id}?user_id=${this.user.id}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    if (res.ok) {
                        const data = await res.json();
                        this.ownerProperties = this.ownerProperties.filter(item => item.id !== id);
                        localStorage.setItem('nks_owner_properties', JSON.stringify(this.ownerProperties));
                        alert('Xóa tin đăng thành công!');
                    } else {
                        const err = await res.json();
                        alert(err.message || 'Xóa tin đăng không thành công.');
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
                    const res = await fetch('/nks-api/login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ email, password })
                    });

                    if (res.ok) {
                        const data = await res.json();
                        localStorage.setItem('nks_user', JSON.stringify(data.user));
                        localStorage.setItem('nks_last_username', email);
                        if (data.access_token) {
                            localStorage.setItem('nks_access_token', data.access_token);
                        }
                        this.lastUsername = email;
                        this.showSecondLogin = true;
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
                const confirmPassword = this.$refs.regPassConfirm ? this.$refs.regPassConfirm.value : '';
                if (password !== confirmPassword) {
                    alert('Mật khẩu nhập lại không khớp. Vui lòng kiểm tra lại.');
                    return;
                }
                
                try {
                    const res = await fetch('/nks-api/register', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
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
                try {
                    const res = await fetch('/nks-api/profile/update', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            email: this.user.email,
                            access_token: localStorage.getItem('nks_access_token') || '',
                            name: (this.firstnameInput + ' ' + this.lastnameInput).trim() || this.user.name,
                            firstname: this.firstnameInput,
                            lastname: this.lastnameInput,
                            intro: this.introInput,
                            phone: this.phoneInput,
                            gender: this.genderInput,
                            website: this.websiteInput,
                            dob: this.dobInput,
                            pob: this.pobInput,
                            id_number: this.idNumberInput,
                            id_date: this.idDateInput,
                            id_place: this.idPlaceInput,
                            province: this.provinceInput
                        })
                    });

                    if (res.ok) {
                        const data = await res.json();
                        this.user = data.user;
                        localStorage.setItem('nks_user', JSON.stringify(this.user));
                        window.dispatchEvent(new CustomEvent('nks-login-change'));
                        alert('Cập nhật thông tin cá nhân thành công!');
                        this.activeStep = 2;
                    } else {
                        const err = await res.json();
                        alert(err.message || 'Cập nhật thất bại.');
                    }
                } catch (e) {
                    alert('Lỗi kết nối CSDL.');
                }
            },

            async loadProvinces() {
                try {
                    const res = await fetch('/nks-api/nks/provinces', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ country_id: 192, slcBox: true })
                    });
                    if (res.ok) {
                        this.provinces = await res.json();
                    }
                } catch (e) {
                    console.error('Failed to load provinces:', e);
                }
            },

            // Avatar upload helpers
            avatarZoom: 1,
            avatarRotate: 0,
            avatarImgSrc: '',
            
            handleAvatarSelect(event) {
                const file = event.target.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.avatarImgSrc = e.target.result;
                    this.avatarZoom = 1;
                    this.avatarRotate = 0;
                };
                reader.readAsDataURL(file);
            },
            
            async saveAvatar() {
                if (!this.avatarImgSrc) {
                    alert('Vui lòng chọn ảnh trước.');
                    return;
                }
                
                const img = new Image();
                img.onload = async () => {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    
                    canvas.width = 300;
                    canvas.height = 300;
                    
                    ctx.clearRect(0, 0, 300, 300);
                    
                    // Draw centered
                    ctx.translate(150, 150);
                    ctx.rotate((this.avatarRotate * Math.PI) / 180);
                    
                    const scale = this.avatarZoom;
                    const w = img.width;
                    const h = img.height;
                    
                    const ratio = Math.max(300 / w, 300 / h) * scale;
                    ctx.drawImage(img, - (w * ratio) / 2, - (h * ratio) / 2, w * ratio, h * ratio);
                    
                    const base64 = canvas.toDataURL('image/jpeg', 0.85);
                    
                    try {
                        const res = await fetch('/nks-api/nks/user/updateAvatar', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                access_token: localStorage.getItem('nks_access_token') || '',
                                avatar: base64
                            })
                        });
                        
                        if (res.ok) {
                            const data = await res.json();
                            const userObj = JSON.parse(localStorage.getItem('nks_user'));
                            userObj.avatar = data.user?.avatar || base64;
                            localStorage.setItem('nks_user', JSON.stringify(userObj));
                            this.user.avatar = userObj.avatar;
                            
                            this.showUpdateAvatarModal = false;
                            this.avatarImgSrc = '';
                            alert('Cập nhật ảnh đại diện thành công!');
                            this.activeStep = 4;
                        } else {
                            const err = await res.json();
                            alert(err.message || 'Lỗi khi cập nhật avatar.');
                        }
                    } catch (e) {
                        alert('Lỗi kết nối máy chủ.');
                    }
                };
                img.src = this.avatarImgSrc;
            },

            // CCCD upload helpers
            cccdFrontSrc: '',
            cccdBackSrc: '',
            
            handleCccdSelect(event, side) {
                const file = event.target.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = (e) => {
                    if (side === 'front') {
                        this.cccdFrontSrc = e.target.result;
                    } else {
                        this.cccdBackSrc = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            },
            
            async saveCccd() {
                if (!this.cccdFrontSrc || !this.cccdBackSrc || !this.cccdNumberInput || !this.cccdDateInput || !this.cccdPlaceInput) {
                    alert('Vui lòng nhập đầy đủ thông tin và chọn ảnh 2 mặt CCCD.');
                    return;
                }
                
                try {
                    const res = await fetch('/nks-api/nks/user/updateCccd', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            access_token: localStorage.getItem('nks_access_token') || '',
                            front: this.cccdFrontSrc,
                            back: this.cccdBackSrc,
                            number: this.cccdNumberInput,
                            date: this.cccdDateInput,
                            place: this.cccdPlaceInput
                        })
                    });
                    
                    if (res.ok) {
                        const data = await res.json();
                        
                        const userObj = JSON.parse(localStorage.getItem('nks_user'));
                        userObj.id_number = this.cccdNumberInput;
                        userObj.id_date = this.cccdDateInput;
                        userObj.id_place = this.cccdPlaceInput;
                        localStorage.setItem('nks_user', JSON.stringify(userObj));
                        
                        this.idNumberInput = this.cccdNumberInput;
                        this.idDateInput = this.cccdDateInput;
                        this.idPlaceInput = this.cccdPlaceInput;
                        
                        this.showUpdateCccdModal = false;
                        alert('Xác thực và cập nhật CCCD thành công!');
                    } else {
                        const err = await res.json();
                        alert(err.message || 'Lỗi khi cập nhật CCCD.');
                    }
                } catch (e) {
                    alert('Lỗi kết nối máy chủ.');
                }
            },
            
            async updatePassword() {
                if (!this.passwordCurrent || !this.passwordNew || !this.passwordConfirm) {
                    alert('Vui lòng nhập đầy đủ mật khẩu hiện tại, mật khẩu mới và xác nhận mật khẩu.');
                    return;
                }
                
                if (this.passwordNew !== this.passwordConfirm) {
                    alert('Mật khẩu mới và mật khẩu xác nhận không trùng khớp.');
                    return;
                }
                
                try {
                    const res = await fetch('/nks-api/nks/user/updatePass', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            access_token: localStorage.getItem('nks_access_token') || '',
                            old_password: this.passwordCurrent,
                            password: this.passwordNew
                        })
                    });
                    
                    if (res.ok) {
                        alert('Cập nhật mật khẩu thành công!');
                        this.passwordCurrent = '';
                        this.passwordNew = '';
                        this.passwordConfirm = '';
                        this.generatedPass = '';
                        this.showPasswordGenerator = false;
                        this.showUpdatePassModal = false;
                        this.activeStep = 3;
                    } else {
                        const err = await res.json();
                        alert(err.message || 'Lỗi khi cập nhật mật khẩu.');
                    }
                } catch (e) {
                    alert('Lỗi kết nối máy chủ.');
                }
            },

            generateRandomPassword() {
                const upperChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                const lowerChars = 'abcdefghijklmnopqrstuvwxyz';
                const numChars = '0123456789';
                const specChars = '!@#$%^&*()_+~`|}{[]:;?><,./-=';
                
                let allowed = '';
                let password = '';
                
                if (this.genUpper) {
                    allowed += upperChars;
                    password += upperChars.charAt(Math.floor(Math.random() * upperChars.length));
                }
                if (this.genLower) {
                    allowed += lowerChars;
                    password += lowerChars.charAt(Math.floor(Math.random() * lowerChars.length));
                }
                if (this.genNumbers) {
                    allowed += numChars;
                    password += numChars.charAt(Math.floor(Math.random() * numChars.length));
                }
                if (this.genSpecial) {
                    allowed += specChars;
                    password += specChars.charAt(Math.floor(Math.random() * specChars.length));
                }
                
                if (allowed === '') {
                    alert('Vui lòng chọn ít nhất một tiêu chí ký tự.');
                    return;
                }
                
                const remainingLength = this.genLength - password.length;
                for (let i = 0; i < remainingLength; i++) {
                    const idx = Math.floor(Math.random() * allowed.length);
                    password += allowed.charAt(idx);
                }
                
                // Shuffle characters
                this.generatedPass = password.split('').sort(() => 0.5 - Math.random()).join('');
                this.hasSavedGenPass = false;
            },
            
            applyGeneratedPassword() {
                if (!this.generatedPass) {
                    alert('Vui lòng bấm tạo mật khẩu trước.');
                    return;
                }
                if (!this.hasSavedGenPass) {
                    alert('Vui lòng tích chọn "Tôi đã lưu mật khẩu mới này" để kích hoạt áp dụng.');
                    return;
                }
                this.passwordNew = this.generatedPass;
                this.passwordConfirm = this.generatedPass;
                this.showPasswordGenerator = false;
            },
            
            async registerHost() {
                if (!this.isLoggedIn) {
                    alert('Vui lòng đăng nhập trước khi đăng ký làm chủ nhà.');
                    this.activeTab = 'login';
                    return;
                }
                
                try {
                    const res = await fetch('/nks-api/profile/upgrade-host', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
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
                        await fetch(`/nks-api/appointments/cancel/${id}`, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
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
                        const res = await fetch('/nks-api/favorites/toggle', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                user_id: this.user.id,
                                property_id: id > 100 ? id : null,
                                external_property_id: id <= 100 ? String(id) : null
                            })
                        });
                        if (!res.ok) {
                            const err = await res.json();
                            alert(err.message || 'Lỗi khi bỏ yêu thích.');
                            return;
                        }
                    } catch (e) {
                        alert('Lỗi kết nối máy chủ CSDL.');
                        return;
                    }
                }
                
                this.favorites = this.favorites.filter(f => f.id !== id);
                localStorage.setItem('nks_favorites', JSON.stringify(this.favorites));
                window.dispatchEvent(new CustomEvent('nks-fav-change'));
            },

            get filteredUsers() {
                if (!this.userSearchQuery) return this.allUsers;
                const query = this.userSearchQuery.toLowerCase();
                return this.allUsers.filter(u => {
                    const nameMatch = u.name ? u.name.toLowerCase().includes(query) : false;
                    const emailMatch = u.email ? u.email.toLowerCase().includes(query) : false;
                    const phoneMatch = u.phone ? u.phone.includes(query) : false;
                    return nameMatch || emailMatch || phoneMatch;
                });
            },

            async loadAllUsers() {
                if (!this.isLoggedIn || !this.user || this.user.role !== 'admin') return;
                this.isLoadingUsers = true;
                try {
                    const res = await fetch(`/nks-api/admin/users?admin_id=${this.user.id}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    if (res.ok) {
                        const data = await res.json();
                        this.allUsers = data.users || [];
                    } else {
                        const err = await res.json();
                        alert(err.message || 'Lỗi nạp danh sách thành viên.');
                    }
                } catch (e) {
                    console.error('Failed to load users:', e);
                } finally {
                    this.isLoadingUsers = false;
                }
            },



            async deleteUser(id) {
                if (parseInt(id) === parseInt(this.user.id)) {
                    alert('Bạn không thể tự xóa tài khoản của chính mình.');
                    return;
                }
                if (!confirm('Bạn có chắc chắn muốn xóa thành viên này? Toàn bộ dữ liệu tin đăng và lịch hẹn liên quan sẽ bị xóa sạch khỏi hệ thống.')) {
                    return;
                }
                try {
                    const res = await fetch(`/nks-api/admin/users/delete/${id}?admin_id=${this.user.id}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    if (res.ok) {
                        this.allUsers = this.allUsers.filter(u => u.id !== id);
                        alert('Đã xóa thành viên thành công.');
                    } else {
                        const err = await res.json();
                        alert(err.message || 'Lỗi xóa thành viên.');
                    }
                } catch (e) {
                    alert('Lỗi kết nối máy chủ CSDL.');
                }
            },

            openAddUserModal() {
                this.addUserForm = { name: '', email: '', password: '', phone: '', role: 'renter', status: 'active' };
                this.showAddUserModal = true;
            },

            async addUser() {
                try {
                    const res = await fetch('/nks-api/admin/users/create', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            admin_id: this.user.id,
                            name: this.addUserForm.name,
                            email: this.addUserForm.email,
                            password: this.addUserForm.password,
                            phone: this.addUserForm.phone,
                            role: this.addUserForm.role,
                            status: this.addUserForm.status
                        })
                    });
                    if (res.ok) {
                        const data = await res.json();
                        this.allUsers = [data.user, ...this.allUsers];
                        this.showAddUserModal = false;
                        alert('Thêm thành viên mới thành công!');
                    } else {
                        const err = await res.json();
                        alert(err.message || 'Lỗi thêm thành viên.');
                    }
                } catch (e) {
                    alert('Lỗi kết nối máy chủ.');
                }
            },

            async toggleUserStatus(id) {
                try {
                    const res = await fetch(`/nks-api/admin/users/toggle-status/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ admin_id: this.user.id })
                    });
                    if (res.ok) {
                        const data = await res.json();
                        const idx = this.allUsers.findIndex(u => u.id === id);
                        if (idx > -1) {
                            this.allUsers.splice(idx, 1, data.user);
                            this.allUsers = [...this.allUsers];
                        }
                        alert(data.user.status === 'blocked' ? 'Đã khóa tài khoản thành viên.' : 'Đã kích hoạt lại tài khoản thành viên.');
                    } else {
                        const err = await res.json();
                        alert(err.message || 'Lỗi đổi trạng thái tài khoản.');
                    }
                } catch (e) {
                    alert('Lỗi kết nối máy chủ.');
                }
            },

            async viewUserDetails(u) {
                // Filter local state first for instant feel
                const userAppointments = this.appointments.filter(a => parseInt(a.user_id) === parseInt(u.id));
                const userProperties = this.ownerProperties.filter(p => parseInt(p.user_id) === parseInt(u.id));
                
                // Fetch emails from emails list
                let userEmails = [];
                if (this.adminEmails.length > 0) {
                    userEmails = this.adminEmails.filter(e => parseInt(e.user_id) === parseInt(u.id) || e.recipient_email === u.email);
                } else {
                    // Try to load emails if they aren't loaded yet
                    try {
                        const res = await fetch(`/nks-api/emails/list?user_id=${this.user.id}`, {
                            method: 'GET',
                            headers: { 'Accept': 'application/json' }
                        });
                        if (res.ok) {
                            const data = await res.json();
                            this.adminEmails = data.logs || [];
                            userEmails = this.adminEmails.filter(e => parseInt(e.user_id) === parseInt(u.id) || e.recipient_email === u.email);
                        }
                    } catch (e) {
                        console.error('Failed to load user emails details', e);
                    }
                }

                this.selectedUserDetails = {
                    name: u.name,
                    email: u.email,
                    role: u.role,
                    status: u.status,
                    appointments: userAppointments,
                    properties: userProperties,
                    emails: userEmails
                };
                this.showUserDetailsModal = true;
            },

            async loadEmails() {
                if (!this.isLoggedIn || !this.user) return;
                this.isLoadingEmails = true;
                try {
                    const res = await fetch(`/nks-api/emails/list?user_id=${this.user.id}`, {
                        method: 'GET',
                        headers: { 'Accept': 'application/json' }
                    });
                    if (res.ok) {
                        const data = await res.json();
                        this.emails = data.logs || [];
                    }
                } catch (e) {
                    console.error('Failed to load emails logs', e);
                } finally {
                    this.isLoadingEmails = false;
                }
            },

            openEmailDetail(email) {
                this.activeEmailDetail = email;
                this.showEmailDetailModal = true;
            },

            async loadAdminEmails() {
                if (!this.isLoggedIn || !this.user || this.user.role !== 'admin') return;
                this.isLoadingAdminEmails = true;
                try {
                    const res = await fetch(`/nks-api/emails/list?user_id=${this.user.id}`, {
                        method: 'GET',
                        headers: { 'Accept': 'application/json' }
                    });
                    if (res.ok) {
                        const data = await res.json();
                        this.adminEmails = data.logs || [];
                    }
                } catch (e) {
                    console.error('Failed to load system emails', e);
                } finally {
                    this.isLoadingAdminEmails = false;
                }
            },

            async fetchMessages() {
                if (!this.isLoggedIn || !this.user) return;
                try {
                    const res = await fetch(`/nks-api/chat/history?user_id=${this.user.id}`, {
                        method: 'GET',
                        headers: { 'Accept': 'application/json' }
                    });
                    if (res.ok) {
                        const data = await res.json();
                        this.chatMessages = data.messages || [];
                        this.$nextTick(() => {
                            const el = this.$refs.chatMessagesContainer;
                            if (el) el.scrollTop = el.scrollHeight;
                        });
                    }
                } catch (e) {
                    console.error('Failed to fetch messages', e);
                }
            },

            startChatPolling() {
                this.fetchMessages();
                this.chatPollingInterval = setInterval(() => {
                    this.fetchMessages();
                }, 5000);
            },

            async sendChatMessage() {
                if (!this.chatInputMessage.trim()) return;
                const body = {
                    sender_id: this.user.id,
                    message: this.chatInputMessage
                };
                this.chatInputMessage = '';
                try {
                    const res = await fetch('/nks-api/chat/send', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(body)
                    });
                    if (res.ok) {
                        this.fetchMessages();
                    }
                } catch (e) {
                    console.error('Failed to send message', e);
                }
            },

            async loadConversations() {
                if (!this.isLoggedIn || !this.user || this.user.role !== 'admin') return;
                try {
                    const res = await fetch(`/nks-api/chat/conversations?admin_id=${this.user.id}`, {
                        method: 'GET',
                        headers: { 'Accept': 'application/json' }
                    });
                    if (res.ok) {
                        const data = await res.json();
                        this.conversations = data.conversations || [];
                    }
                } catch (e) {
                    console.error('Failed to load conversations', e);
                }
            },

            async fetchAdminClientMessages() {
                if (!this.activeChatClient) return;
                try {
                    const res = await fetch(`/nks-api/chat/history?user_id=${this.user.id}&client_id=${this.activeChatClient.id}`, {
                        method: 'GET',
                        headers: { 'Accept': 'application/json' }
                    });
                    if (res.ok) {
                        const data = await res.json();
                        this.adminChatMessages = data.messages || [];
                        this.$nextTick(() => {
                            const el = this.$refs.adminChatContainer;
                            if (el) el.scrollTop = el.scrollHeight;
                        });
                    }
                } catch (e) {
                    console.error('Failed to fetch client messages', e);
                }
            },

            async selectConversation(client) {
                this.activeChatClient = client;
                this.fetchAdminClientMessages();
            },

            async sendAdminChatMessage() {
                if (!this.adminChatInputMessage.trim() || !this.activeChatClient) return;
                const body = {
                    sender_id: this.user.id,
                    receiver_id: this.activeChatClient.id,
                    message: this.adminChatInputMessage
                };
                this.adminChatInputMessage = '';
                try {
                    const res = await fetch('/nks-api/chat/send', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(body)
                    });
                    if (res.ok) {
                        this.fetchAdminClientMessages();
                        this.loadConversations();
                    }
                } catch (e) {
                    console.error('Failed to send admin reply', e);
                }
            },

            startAdminChatPolling() {
                this.loadConversations();
                this.adminChatPollingInterval = setInterval(() => {
                    this.loadConversations();
                    if (this.activeChatClient) {
                        this.fetchAdminClientMessages();
                    }
                }, 5000);
            }
        }));
    });
</script>
@endsection
