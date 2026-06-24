<!DOCTYPE html>
<html lang="vi" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- SEO Optimization -->
    <title>@yield('title', 'NKS - Website Cho Thuê Bất Động Sản Chính Chủ TPHCM')</title>
    <meta name="description" content="@yield('meta_description', 'Tìm kiếm căn hộ, nhà phố, biệt thự cho thuê chính chủ tại TP. Hồ Chí Minh nhanh chóng, tiện lợi, bản đồ tương tác MapLibre hiện đại.')">
    <meta name="keywords" content="cho thue nha dat, thue can ho, bds chinh chu, bat dong san nks, moso, thue nha tphcm">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', 'NKS - Cho Thuê Bất Động Sản Chính Chủ')">
    <meta property="og:description" content="@yield('meta_description', 'Tìm kiếm căn hộ, nhà phố cho thuê chính chủ tại TP. Hồ Chí Minh trên bản đồ tương tác.')">
    <meta property="og:image" content="{{ asset('images/og-share.jpg') }}">

    <!-- MapLibre CSS & JS -->
    <link href="https://unpkg.com/maplibre-gl@4.7.1/dist/maplibre-gl.css" rel="stylesheet" />
    <script src="https://unpkg.com/maplibre-gl@4.7.1/dist/maplibre-gl.js"></script>

    <!-- Asset Compilations -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @yield('styles')
</head>
<body class="flex flex-col min-h-screen bg-white text-slate-800 font-sans antialiased"
      x-data="{ 
          mobileMenuOpen: false, 
          isLoggedIn: false,
          user: null,
          addressToggle: false,
          idleInterval: null,
          pingInterval: null,
          
          resetIdleTimer() {
              if (!this.isLoggedIn) {
                  return;
              }
              localStorage.setItem('nks_last_activity', Date.now().toString());
          },

          init() {
              // 1. Check if idle timeout has expired while tab/browser was closed
              const savedUser = localStorage.getItem('nks_user');
              if (savedUser) {
                  const lastAct = parseInt(localStorage.getItem('nks_last_activity') || '0');
                  const now = Date.now();
                  if (lastAct > 0 && now - lastAct >= 300000) {
                      localStorage.removeItem('nks_user');
                      localStorage.removeItem('nks_appointments');
                      localStorage.removeItem('nks_favorites');
                      localStorage.removeItem('nks_owner_properties');
                      localStorage.removeItem('nks_access_token');
                      localStorage.removeItem('nks_last_activity');
                      this.isLoggedIn = false;
                      this.user = null;
                  } else {
                      this.isLoggedIn = true;
                      this.user = JSON.parse(savedUser);
                  }
              }
              
              // 2. Listen to login change events (local tab scope)
              window.addEventListener('nks-login-change', () => {
                  const saved = localStorage.getItem('nks_user');
                  if (saved) {
                      this.isLoggedIn = true;
                      this.user = JSON.parse(saved);
                      this.resetIdleTimer();
                      this.startIdleMonitoring();
                  } else {
                      this.isLoggedIn = false;
                      this.user = null;
                      this.stopIdleMonitoring();
                  }
              });

              // 3. Register user activity listeners once
              const events = ['mousemove', 'keydown', 'click', 'scroll', 'touchstart'];
              events.forEach(event => {
                  window.addEventListener(event, () => this.resetIdleTimer(), { passive: true });
              });

              // 4. Multi-tab synchronization via storage events
              window.addEventListener('storage', (e) => {
                  if (e.key === 'nks_user') {
                      if (!e.newValue) {
                          this.isLoggedIn = false;
                          this.user = null;
                          this.stopIdleMonitoring();
                          window.location.href = '/';
                      } else {
                          this.isLoggedIn = true;
                          this.user = JSON.parse(e.newValue);
                          this.resetIdleTimer();
                          this.startIdleMonitoring();
                      }
                  }
              });
              
              if (this.isLoggedIn) {
                  this.resetIdleTimer();
                  this.startIdleMonitoring();
              }
          },

          startIdleMonitoring() {
              this.stopIdleMonitoring();

              // Run check every 2 seconds for inactivity across all tabs
              this.idleInterval = setInterval(() => {
                  if (!this.isLoggedIn) return;
                  
                  const lastAct = parseInt(localStorage.getItem('nks_last_activity') || '0');
                  const now = Date.now();
                  const idleTime = now - lastAct;

                  if (lastAct > 0 && idleTime >= 300000) { // 5 minutes
                      this.stopIdleMonitoring();
                      
                      // Only trigger alert on the first tab to clean up
                      if (localStorage.getItem('nks_user')) {
                          alert('Phiên đăng nhập đã hết hạn do không có hoạt động trong 5 phút. Vui lòng đăng nhập lại.');
                          this.logout();
                      } else {
                          this.isLoggedIn = false;
                          this.user = null;
                          window.location.href = '/';
                      }
                  }
              }, 2000);

              // Server keep-alive ping every 2 minutes when user is active
              this.pingInterval = setInterval(() => {
                  if (!this.isLoggedIn) return;
                  
                  const lastAct = parseInt(localStorage.getItem('nks_last_activity') || '0');
                  const now = Date.now();
                  
                  if (lastAct > 0 && now - lastAct < 120000) {
                      fetch('/nks-api/ping', {
                          method: 'GET',
                          headers: {
                              'Accept': 'application/json',
                              'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                          }
                      }).catch(() => {});
                  }
              }, 120000);
          },

          stopIdleMonitoring() {
              if (this.idleInterval) {
                  clearInterval(this.idleInterval);
                  this.idleInterval = null;
              }
              if (this.pingInterval) {
                  clearInterval(this.pingInterval);
                  this.pingInterval = null;
              }
          },

          async logout() {
              this.stopIdleMonitoring();
              try {
                  await fetch('/nks-api/logout', {
                      method: 'POST',
                      headers: {
                          'Accept': 'application/json',
                          'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                      }
                  });
              } catch (e) {}
              localStorage.removeItem('nks_user');
              localStorage.removeItem('nks_appointments');
              localStorage.removeItem('nks_favorites');
              localStorage.removeItem('nks_owner_properties');
              localStorage.removeItem('nks_access_token');
              localStorage.removeItem('nks_last_activity');
              this.isLoggedIn = false;
              this.user = null;
              window.dispatchEvent(new CustomEvent('nks-login-change'));
              window.location.href = '/';
          }
      }">

    <!-- Header Navigation (Exact Moso UX & Layout) -->
    <header class="sticky top-0 z-50 w-full transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                
                <!-- Mobile Hamburger Button (Left on mobile, hidden on desktop) -->
                <div class="flex lg:hidden items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" 
                            class="text-slate-600 hover:text-primary focus:outline-none p-2 rounded-xl hover:bg-slate-50 transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" style="display: none;"/>
                        </svg>
                    </button>
                </div>

                <!-- Left Menu: Mua, Thuê, Kho dự án, Vay thế chấp, Đối tác -->
                <div class="hidden lg:flex items-center space-x-6">
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="text-sm font-bold text-slate-800 hover:text-primary flex items-center gap-1 focus:outline-none transition-colors">
                            Mua
                            <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="open" class="absolute left-0 mt-3 w-40 bg-white border border-slate-100 shadow-xl rounded-xl py-2 z-50" style="display: none;">
                            <a href="/properties?action=buy" class="block px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-primary">Nhà mặt tiền</a>
                            <a href="/properties?action=buy" class="block px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-primary">Căn hộ chung cư</a>
                        </div>
                    </div>

                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="text-sm font-bold text-slate-800 hover:text-primary flex items-center gap-1 focus:outline-none transition-colors">
                            Thuê
                            <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="open" class="absolute left-0 mt-3 w-40 bg-white border border-slate-100 shadow-xl rounded-xl py-2 z-50" style="display: none;">
                            <a href="/properties?action=rent" class="block px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-primary">Thuê căn hộ</a>
                            <a href="/properties?action=rent" class="block px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-primary">Thuê nhà nguyên căn</a>
                        </div>
                    </div>

                    <a href="/properties" class="text-sm font-bold text-slate-800 hover:text-primary transition-colors">Kho dự án</a>
                    <a href="#" class="text-sm font-bold text-slate-800 hover:text-primary transition-colors">Vay thế chấp</a>
                    
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="text-sm font-bold text-slate-800 hover:text-primary flex items-center gap-1 focus:outline-none transition-colors">
                            Đối tác
                            <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="open" class="absolute left-0 mt-3 w-40 bg-white border border-slate-100 shadow-xl rounded-xl py-2 z-50" style="display: none;">
                            <a href="/profile?tab=host" class="block px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-primary">Chủ sở hữu</a>
                            <a href="#" class="block px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-primary">Nhà môi giới</a>
                        </div>
                    </div>
                </div>

                <!-- Center Logo: NKS stylized exactly like MOSO (Text + stylized circle dot) -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="flex items-center gap-1 group">
                        <span class="text-2xl font-black tracking-tight text-slate-900 leading-none group-hover:text-primary transition-colors">N</span>
                        <!-- Stylized blue circle with white gap like moso orange circle -->
                        <div class="w-4.5 h-4.5 rounded-full border-4 border-primary flex items-center justify-center -mt-0.5"></div>
                        <span class="text-2xl font-black tracking-tight text-slate-900 leading-none group-hover:text-primary transition-colors">S</span>
                    </a>
                </div>

                <!-- Actions / Auth - Right side -->
                <div class="flex items-center gap-6">
                    <!-- "Địa chỉ mới" toggle switch -->
                    <div class="hidden sm:flex items-center gap-2">
                        <span class="text-xs font-bold text-slate-500">Địa chỉ mới</span>
                        <button @click="addressToggle = !addressToggle" 
                                class="w-10 h-5.5 rounded-full p-0.5 transition-colors duration-200 focus:outline-none relative"
                                :class="addressToggle ? 'bg-primary' : 'bg-slate-200'">
                            <div class="w-4.5 h-4.5 rounded-full bg-white shadow-sm transition-transform duration-200"
                                 :class="addressToggle ? 'translate-x-4.5' : 'translate-x-0'"></div>
                        </button>
                    </div>

                    <!-- "Đăng tin" button (Exact orange/blue accent equivalent) -->
                    <a :href="isLoggedIn && user && user.role === 'owner' ? '/profile?tab=properties' : '/profile?tab=host'" class="bg-primary hover:bg-primary-dark text-white text-xs font-bold px-5 py-2.5 rounded-[12px] shadow-sm hover:shadow transition-all duration-300">
                        Đăng tin
                    </a>

                    <!-- User Account / Avatar Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="w-9 h-9 rounded-full overflow-hidden border border-slate-200 focus:outline-none bg-slate-50">
                            <img :src="isLoggedIn && user && user.avatar ? user.avatar : 'https://api.dicebear.com/7.x/adventurer/svg?seed=nks'" alt="Avatar" class="w-full h-full object-cover">
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                             class="absolute right-0 mt-3 w-52 rounded-2xl bg-white border border-slate-100 shadow-xl py-2 z-50"
                             style="display: none;">
                            <template x-if="!isLoggedIn">
                                <div>
                                    <a href="/profile?tab=login" class="block px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-primary">Đăng nhập</a>
                                    <a href="/profile?tab=register" class="block px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-primary">Đăng ký thành viên</a>
                                </div>
                            </template>
                            <template x-if="isLoggedIn">
                                <div>
                                    <div class="px-4 py-2 border-b border-slate-50">
                                        <div class="flex items-center justify-between gap-2">
                                            <p class="text-xs text-slate-400">Tài khoản</p>
                                            <template x-if="user && user.id_number">
                                                <span class="inline-flex items-center gap-0.5 bg-emerald-500/10 text-emerald-600 px-1.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wide border border-emerald-200/30">
                                                    <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Đã xác minh
                                                </span>
                                            </template>
                                        </div>
                                        <p class="text-xs font-bold text-slate-700 truncate" x-text="user ? user.email : ''"></p>
                                    </div>
                                    <a href="/profile?tab=info" class="block px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-primary">Hồ sơ cá nhân</a>
                                    <a href="/profile?tab=favorites" class="block px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-primary">Tin đã yêu thích</a>
                                    <a href="/profile?tab=appointments" class="block px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-primary">Lịch hẹn xem nhà</a>
                                    <button @click="logout()" class="w-full text-left block px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-50">Đăng xuất</button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </header>

    <!-- Mobile Menu Drawer (Slide-in from left) -->
        <div x-show="mobileMenuOpen" 
             class="fixed inset-0 z-[60] lg:hidden" 
             style="display: none;"
             x-cloak>
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity duration-300"
                 x-show="mobileMenuOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="mobileMenuOpen = false"></div>

            <!-- Drawer Body -->
            <div class="absolute inset-y-0 left-0 max-w-xs w-full bg-white shadow-2xl flex flex-col z-10 transition-transform duration-300"
                 x-show="mobileMenuOpen"
                 x-transition:enter="transform ease-out duration-300"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transform ease-in duration-200"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full">
                 
                 <!-- Drawer Header -->
                 <div class="flex items-center justify-between h-20 px-6 border-b border-slate-100 flex-shrink-0">
                     <!-- Logo -->
                     <a href="/" class="flex items-center gap-1 group" @click="mobileMenuOpen = false">
                         <span class="text-xl font-black tracking-tight text-slate-900 leading-none">N</span>
                         <div class="w-4 h-4 rounded-full border-4 border-primary flex items-center justify-center -mt-0.5"></div>
                         <span class="text-xl font-black tracking-tight text-slate-900 leading-none">S</span>
                     </a>
                     <!-- Close Button -->
                     <button @click="mobileMenuOpen = false" 
                             class="w-9 h-9 rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100 hover:text-slate-700 flex items-center justify-center transition-colors">
                         <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                             <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                         </svg>
                     </button>
                 </div>

                 <!-- Drawer Links (Scrollable) -->
                 <div class="flex-grow overflow-y-auto py-6 px-6 space-y-6">
                     
                     <!-- Category: Mua -->
                     <div class="space-y-3" x-data="{ open: false }">
                         <button @click="open = !open" class="w-full flex items-center justify-between text-sm font-black text-slate-800 focus:outline-none">
                             Mua Bất Động Sản
                             <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                         </button>
                         <div x-show="open" x-transition class="pl-4 space-y-2 border-l border-slate-100">
                             <a href="/properties?action=buy" @click="mobileMenuOpen = false" class="block py-1 text-xs font-bold text-slate-500 hover:text-primary">Nhà mặt tiền</a>
                             <a href="/properties?action=buy" @click="mobileMenuOpen = false" class="block py-1 text-xs font-bold text-slate-500 hover:text-primary">Căn hộ chung cư</a>
                         </div>
                     </div>

                     <!-- Category: Thuê -->
                     <div class="space-y-3" x-data="{ open: false }">
                         <button @click="open = !open" class="w-full flex items-center justify-between text-sm font-black text-slate-800 focus:outline-none">
                             Thuê Bất Động Sản
                             <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                         </button>
                         <div x-show="open" x-transition class="pl-4 space-y-2 border-l border-slate-100">
                             <a href="/properties?action=rent" @click="mobileMenuOpen = false" class="block py-1 text-xs font-bold text-slate-500 hover:text-primary">Thuê căn hộ</a>
                             <a href="/properties?action=rent" @click="mobileMenuOpen = false" class="block py-1 text-xs font-bold text-slate-500 hover:text-primary">Thuê nhà nguyên căn</a>
                         </div>
                     </div>

                     <!-- Direct Links -->
                     <div class="space-y-4">
                         <a href="/properties" @click="mobileMenuOpen = false" class="block text-sm font-black text-slate-800 hover:text-primary transition-colors">Kho dự án</a>
                         <a href="#" @click="mobileMenuOpen = false" class="block text-sm font-black text-slate-800 hover:text-primary transition-colors">Vay thế chấp</a>
                     </div>

                     <!-- Category: Đối tác -->
                     <div class="space-y-3" x-data="{ open: false }">
                         <button @click="open = !open" class="w-full flex items-center justify-between text-sm font-black text-slate-800 focus:outline-none">
                             Đối tác
                             <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                         </button>
                         <div x-show="open" x-transition class="pl-4 space-y-2 border-l border-slate-100">
                             <a href="/profile?tab=host" @click="mobileMenuOpen = false" class="block py-1 text-xs font-bold text-slate-500 hover:text-primary">Chủ sở hữu</a>
                             <a href="#" @click="mobileMenuOpen = false" class="block py-1 text-xs font-bold text-slate-500 hover:text-primary">Nhà môi giới</a>
                         </div>
                     </div>

                 </div>

                 <!-- Drawer Footer -->
                 <div class="p-6 border-t border-slate-100 space-y-4 bg-slate-50">
                     <!-- "Địa chỉ mới" toggle for mobile -->
                     <div class="flex items-center justify-between">
                         <span class="text-xs font-bold text-slate-500">Địa chỉ mới</span>
                         <button @click="addressToggle = !addressToggle" 
                                 class="w-10 h-5.5 rounded-full p-0.5 transition-colors duration-200 focus:outline-none relative"
                                 :class="addressToggle ? 'bg-primary' : 'bg-slate-200'">
                             <div class="w-4.5 h-4.5 rounded-full bg-white shadow-sm transition-transform duration-200"
                                  :class="addressToggle ? 'translate-x-4.5' : 'translate-x-0'"></div>
                         </button>
                     </div>
                 </div>
            </div>
        </div>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-16 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                <!-- Branding Info -->
                <div class="space-y-6">
                    <a href="/" class="flex items-center gap-1 group">
                        <span class="text-2xl font-black text-white leading-none">N</span>
                        <div class="w-4.5 h-4.5 rounded-full border-4 border-primary flex items-center justify-center -mt-0.5"></div>
                        <span class="text-2xl font-black text-white leading-none">S</span>
                    </a>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Hệ thống thông tin Bất Động Sản Chính Chủ uy tín hàng đầu tại TP. Hồ Chí Minh. Chúng tôi kết nối trực tiếp chủ nhà và khách thuê không qua trung gian.
                    </p>
                </div>

                <!-- Navigation Links -->
                <div>
                    <h3 class="text-white text-xs font-bold tracking-wider uppercase mb-6">Liên kết nhanh</h3>
                    <ul class="space-y-3 text-xs">
                        <li><a href="/" class="hover:text-white transition-colors duration-300">Trang chủ</a></li>
                        <li><a href="/properties" class="hover:text-white transition-colors duration-300">Bản đồ thuê</a></li>
                        <li><a href="/profile?tab=host" class="hover:text-white transition-colors duration-300">Đăng ký làm chủ nhà</a></li>
                    </ul>
                </div>

                <!-- Contact Information -->
                <div class="lg:col-span-2 space-y-4">
                    <h3 class="text-white text-xs font-bold tracking-wider uppercase mb-6">Thông tin liên hệ</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-primary flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                            <span class="text-slate-400">222 Lê Văn Sỹ, Phường Nhiêu Lộc, Phú Nhuận, TPHCM</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-primary flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            <a href="tel:+84932030958" class="hover:text-white">(+84) 932.030.958</a>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-primary flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            <a href="mailto:system@nks.vn" class="hover:text-white">system@nks.vn</a>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-primary flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
                            <a href="http://www.nks.com.vn" class="hover:text-white">www.nks.com.vn</a>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-slate-800 my-10">

            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 text-[10px]">
                <p>&copy; {{ date('Y') }} BDS NKS. Bản quyền đã được bảo hộ.</p>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-white transition-colors">Điều khoản sử dụng</a>
                    <a href="#" class="hover:text-white transition-colors">Chính sách bảo mật</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- GLOBAL TOAST NOTIFICATION -->
    <div x-data="{
        show: false,
        message: '',
        type: 'success',
        init() {
            window.addEventListener('nks-toast', (e) => {
                this.message = e.detail.message;
                this.type = e.detail.type || 'success';
                this.show = true;
                setTimeout(() => this.show = false, 3000);
            });
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300 transform"
    x-transition:enter-start="-translate-y-12 opacity-0"
    x-transition:enter-end="translate-y-0 opacity-100"
    x-transition:leave="transition ease-in duration-200 transform"
    x-transition:leave-start="translate-y-0 opacity-100"
    x-transition:leave-end="-translate-y-12 opacity-0"
    class="fixed top-6 left-1/2 transform -translate-x-1/2 z-[110] max-w-sm w-full px-4"
    style="display: none;"
    x-cloak>
        <div class="bg-slate-900/95 backdrop-blur-md text-white rounded-2xl px-5 py-3.5 shadow-2xl border border-slate-800 flex items-center gap-3">
            <template x-if="type === 'success'">
                <div class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                </div>
            </template>
            <template x-if="type === 'info'">
                <div class="w-6 h-6 rounded-full bg-primary/20 text-primary flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </template>
            <p class="text-xs font-semibold tracking-wide" x-text="message"></p>
        </div>
    </div>

    <!-- GLOBAL SHARE MODAL -->
    <div x-data="{
        open: false,
        url: '',
        title: '',
        copied: false,
        init() {
            window.addEventListener('open-share-modal', (e) => {
                this.url = e.detail.url;
                this.title = e.detail.title;
                this.open = true;
                this.copied = false;
            });
        },
        copyToClipboard() {
            navigator.clipboard.writeText(this.url).then(() => {
                this.copied = true;
                window.dispatchEvent(new CustomEvent('nks-toast', { 
                    detail: { message: 'Đã sao chép liên kết thành công!', type: 'success' } 
                }));
                setTimeout(() => this.copied = false, 2000);
            }).catch(err => {
                console.error('Copy failed', err);
            });
        }
    }"
    x-show="open"
    class="fixed inset-0 z-[100] flex items-center justify-center p-4"
    style="display: none;"
    x-cloak>
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
             x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="open = false"></div>

        <!-- Modal Container -->
        <div class="bg-white rounded-[32px] shadow-2xl max-w-md w-full overflow-hidden border border-slate-100 relative z-10 p-6 sm:p-8"
             x-show="open"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="-translate-y-4 opacity-0 scale-95">
             
             <!-- Close Button -->
             <button @click="open = false" 
                     class="absolute top-4 right-4 w-9 h-9 rounded-full bg-slate-50 hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center transition-colors">
                 <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                     <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                 </svg>
             </button>

             <!-- Title -->
             <div class="text-center space-y-2 mb-6">
                 <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center mx-auto">
                     <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                         <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 10.742l4.622-2.311m0 0a3 3 0 10-2.667-1.772a3 3 0 002.667 1.772zm0 6.518l-4.623-2.311a3 3 0 11-2.667-1.772a3 3 0 012.667 1.772zm1.144 0a3 3 0 112.667 1.772a3 3 0 01-2.667-1.772z" />
                     </svg>
                 </div>
                 <h3 class="text-lg font-black text-slate-800 tracking-tight">Chia sẻ tin đăng</h3>
                 <p class="text-xs text-slate-400 font-bold px-4 truncate" x-text="title"></p>
             </div>

             <!-- Share Buttons Grid -->
             <div class="grid grid-cols-3 gap-4 mb-6">
                 <!-- Facebook -->
                 <a :href="'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(url)" 
                    target="_blank"
                    class="flex flex-col items-center gap-2 p-3 rounded-2xl border border-slate-100 hover:border-blue-100 hover:bg-blue-50/30 transition-all duration-300 group">
                     <div class="w-10 h-10 rounded-full bg-[#1877F2]/10 text-[#1877F2] flex items-center justify-center group-hover:scale-110 transition-transform">
                         <svg class="w-5 h-5 fill-currentColor" viewBox="0 0 24 24">
                             <path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c4.56-.93 8-4.96 8-9.75z"/>
                         </svg>
                     </div>
                     <span class="text-[10px] font-bold text-slate-600">Facebook</span>
                 </a>

                 <!-- Zalo -->
                 <a :href="'https://sp.zalo.me/share_to_zalo?url=' + encodeURIComponent(url)" 
                    target="_blank"
                    class="flex flex-col items-center gap-2 p-3 rounded-2xl border border-slate-100 hover:border-sky-100 hover:bg-sky-50/30 transition-all duration-300 group">
                     <div class="w-10 h-10 rounded-full bg-[#0068FF]/10 text-[#0068FF] flex items-center justify-center group-hover:scale-110 transition-transform font-black text-sm">
                         Zalo
                     </div>
                     <span class="text-[10px] font-bold text-slate-600">Zalo</span>
                 </a>

                 <!-- Telegram -->
                 <a :href="'https://t.me/share/url?url=' + encodeURIComponent(url) + '&text=' + encodeURIComponent(title)" 
                    target="_blank"
                    class="flex flex-col items-center gap-2 p-3 rounded-2xl border border-slate-100 hover:border-cyan-100 hover:bg-cyan-50/30 transition-all duration-300 group">
                     <div class="w-10 h-10 rounded-full bg-[#229ED9]/10 text-[#229ED9] flex items-center justify-center group-hover:scale-110 transition-transform">
                         <svg class="w-5 h-5 fill-currentColor" viewBox="0 0 24 24">
                             <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69.01-.03.01-.14-.07-.2-.08-.06-.19-.04-.27-.02-.12.02-1.96 1.24-5.54 3.65-.52.36-.99.53-1.4.52-.46-.01-1.34-.26-2-.47-.8-.26-1.43-.4-1.38-.85.03-.23.35-.47.96-.71 3.76-1.64 6.27-2.72 7.53-3.25 3.58-1.5 4.32-1.76 4.81-1.77.11 0 .35.03.5.15.13.1.17.24.18.33-.02.09 0 .19-.01.26z"/>
                         </svg>
                     </div>
                     <span class="text-[10px] font-bold text-slate-600">Telegram</span>
                 </a>
             </div>

             <!-- Copy link text container -->
             <div class="space-y-2">
                 <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider">Đường dẫn liên kết</label>
                 <div class="flex items-center gap-2 p-1.5 bg-slate-50 rounded-2xl border border-slate-100">
                     <input type="text" 
                            readonly 
                            :value="url" 
                            class="bg-transparent border-none text-xs font-bold text-slate-500 flex-grow pl-3 focus:outline-none select-all truncate">
                     <button @click="copyToClipboard()" 
                             class="bg-primary hover:bg-primary-dark text-white font-extrabold text-xs px-4 py-2.5 rounded-xl shadow-xs transition-all active:scale-95 flex items-center gap-1.5">
                         <span x-text="copied ? 'Đã sao chép' : 'Sao chép'"></span>
                     </button>
                 </div>
             </div>
        </div>
    </div>

    <script>
        window.openShare = function(url, title) {
            window.dispatchEvent(new CustomEvent('open-share-modal', { detail: { url: url, title: title } }));
        };
    </script>

    <!-- AI Chatbot Floating Widget -->
    <div x-data="nksAiChatbot()" 
         x-init="initChatbot()"
         class="fixed bottom-6 right-6 z-50 flex flex-col items-end">
         
        <!-- Chat Window Panel -->
        <div x-show="isOpen"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 scale-95"
             class="mb-4 w-96 max-w-[calc(100vw-2rem)] h-[520px] max-h-[calc(100vh-10rem)] bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden flex flex-col"
             style="display: none;">
             
             <!-- Header -->
             <div class="bg-gradient-to-r from-primary to-indigo-600 px-5 py-4 flex items-center justify-between text-white shadow-md">
                 <div class="flex items-center gap-3">
                     <div class="relative">
                         <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center border border-white/20">
                             <!-- Robot Icon -->
                             <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                 <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                             </svg>
                         </div>
                         <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-400 border-2 border-primary rounded-full animate-pulse"></span>
                     </div>
                     <div>
                         <h4 class="font-black text-sm tracking-wide">Trợ Lý Ảo NKS</h4>
                         <p class="text-[10px] text-white/80 font-bold flex items-center gap-1">
                             <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
                             Trực tuyến • Sẵn sàng tư vấn
                         </p>
                     </div>
                 </div>
                 
                 <!-- Close Button -->
                 <button @click="isOpen = false" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors focus:outline-none">
                     <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                         <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                     </svg>
                 </button>
             </div>
             
             <!-- Messages Body -->
             <div x-ref="messageContainer" class="flex-grow overflow-y-auto px-5 py-5 space-y-4 bg-slate-50/50">
                 <!-- Welcome Message -->
                 <div class="flex gap-2.5 max-w-[85%]">
                     <div class="w-8 h-8 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center shrink-0">
                         <svg class="w-4.5 h-4.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                             <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                         </svg>
                     </div>
                     <div class="bg-white rounded-2xl rounded-tl-none px-4 py-3 border border-slate-100 shadow-sm text-sm text-slate-700 leading-relaxed">
                         Chào <span x-text="getGenderHonorific()">anh/chị</span>! Em là trợ lý ảo của BDS NKS. Em có thể hỗ trợ <span x-text="getGenderHonorific()">anh/chị</span> tìm kiếm thông tin gì hôm nay ạ? <span class="capitalize" x-text="getGenderHonorific()">Anh/chị</span> có thể hỏi em về:
                         <ul class="list-disc pl-4 mt-1.5 space-y-1 text-slate-600">
                             <li>Tìm nhà/căn hộ thuê theo khu vực & tầm giá</li>
                             <li>Thủ tục đặt cọc, pháp lý thuê nhà</li>
                             <li>Tính toán lãi vay mua nhà trả góp</li>
                         </ul>
                     </div>
                 </div>
                 
                 <!-- Chat messages dynamic list -->
                 <template x-for="msg in messages" :key="msg.id">
                     <div :class="msg.role === 'user' ? 'justify-end' : ''" class="flex gap-2.5 items-start w-full">
                         <!-- Bot Avatar -->
                         <template x-if="msg.role !== 'user'">
                             <div class="w-8 h-8 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center shrink-0">
                                 <svg class="w-4.5 h-4.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                     <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                 </svg>
                             </div>
                         </template>
                         
                         <!-- Message bubble -->
                         <div :class="msg.role === 'user' ? 'bg-primary text-white rounded-tr-none ml-auto shadow-sm shadow-primary/10' : 'bg-white text-slate-700 border border-slate-100 rounded-tl-none shadow-sm'" 
                              class="rounded-2xl px-4 py-3 text-sm leading-relaxed whitespace-pre-line break-words max-w-[85%]"
                              x-html="renderMarkdown(msg.content)">
                         </div>
                     </div>
                 </template>
                 
                 <!-- Typing Indicator -->
                 <div x-show="isTyping" class="flex gap-2.5 max-w-[85%] items-start" style="display: none;">
                     <div class="w-8 h-8 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center shrink-0">
                         <svg class="w-4.5 h-4.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                             <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                         </svg>
                     </div>
                     <div class="bg-white rounded-2xl rounded-tl-none px-4 py-3.5 border border-slate-100 shadow-sm flex items-center gap-1.5">
                         <span class="w-2 h-2 rounded-full bg-indigo-500 animate-bounce" style="animation-delay: 0ms"></span>
                         <span class="w-2 h-2 rounded-full bg-indigo-500 animate-bounce" style="animation-delay: 150ms"></span>
                         <span class="w-2 h-2 rounded-full bg-indigo-500 animate-bounce" style="animation-delay: 300ms"></span>
                     </div>
                 </div>
             </div>
             
             <!-- Suggestion Tags -->
             <div class="px-4 py-2 bg-slate-50 border-t border-slate-100">
                 <div class="flex gap-2 overflow-x-auto pb-1.5 pt-0.5 scrollbar-none snap-x snap-mandatory">
                     <template x-for="tag in suggestions" :key="tag">
                         <button @click="sendSuggestion(tag)" 
                                 class="shrink-0 snap-start px-3 py-1.5 rounded-full bg-white hover:bg-indigo-50 border border-slate-100 text-xs font-bold text-slate-600 hover:text-indigo-600 hover:border-indigo-200 transition-all shadow-2xs active:scale-95">
                             <span x-text="tag"></span>
                         </button>
                     </template>
                 </div>
             </div>
             
             <!-- Input area -->
             <form @submit.prevent="sendMessage()" class="p-4 bg-white border-t border-slate-100 flex items-center gap-2">
                 <input type="text"
                        x-model="inputText"
                        placeholder="Nhập câu hỏi của bạn..."
                        class="flex-grow px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/10 transition-all font-bold placeholder-slate-400 text-slate-700"
                        :disabled="isTyping">
                 <button type="submit"
                         :disabled="!inputText.trim() || isTyping"
                         class="w-11 h-11 rounded-2xl bg-primary hover:bg-primary-dark text-white flex items-center justify-center transition-all shadow-sm active:scale-95 disabled:opacity-40 disabled:scale-100 focus:outline-none">
                     <svg class="w-5 h-5 transform rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                         <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                     </svg>
                 </button>
             </form>
        </div>
        
        <!-- Toggle Floating Button -->
        <button @click="toggleChat()"
                class="w-14 h-14 rounded-full bg-gradient-to-tr from-primary to-indigo-600 hover:from-primary-dark hover:to-indigo-700 text-white flex items-center justify-center shadow-lg shadow-primary/30 transform active:scale-95 hover:scale-105 transition-all duration-300 relative group focus:outline-none">
            
            <!-- Pulsing Ring when chat is closed -->
            <span x-show="!isOpen" class="absolute -inset-0.5 rounded-full bg-primary/30 animate-ping opacity-75 group-hover:opacity-100 transition-opacity"></span>
            
            <!-- Toggle icons -->
            <div class="relative z-10">
                <!-- Bot Icon (when closed) -->
                <svg x-show="!isOpen" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <!-- Close Icon (when open) -->
                <svg x-show="isOpen" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
        </button>
    </div>

    <script>
        window.nksAiChatbot = function() {
            return {
                isOpen: false,
                messages: [],
                isTyping: false,
                inputText: '',
                sessionId: '',
                suggestions: [
                    'Tìm thuê nhà Quận 7',
                    'Thuê căn hộ Bình Thạnh',
                    'Thủ tục cọc thuê nhà',
                    'Tư vấn vay vốn ngân hàng'
                ],

                initChatbot() {
                    // Generate fresh session ID on every page load to reset conversation
                    this.sessionId = 'ai_session_' + Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
                    this.messages = [];
                },

                getGenderHonorific() {
                    const savedUser = localStorage.getItem('nks_user');
                    if (savedUser) {
                        try {
                            const parsed = JSON.parse(savedUser);
                            if (parsed.gender !== undefined && parsed.gender !== null) {
                                const g = parseInt(parsed.gender);
                                if (g === 0) return 'anh';
                                if (g === 1) return 'chị';
                            }
                        } catch(e) {}
                    }
                    return 'anh/chị';
                },

                async loadChatHistory() {
                    try {
                        const response = await fetch(`/nks-api/ai/chat/history?session_id=${this.sessionId}`);
                        const data = await response.json();
                        if (data.success && data.messages) {
                            this.messages = data.messages;
                            this.scrollToBottom();
                        }
                    } catch (e) {
                        console.error('Failed to load AI chat history:', e);
                    }
                },

                toggleChat() {
                    this.isOpen = !this.isOpen;
                    if (this.isOpen) {
                        this.scrollToBottom();
                    }
                },

                sendSuggestion(text) {
                    this.inputText = text;
                    this.sendMessage();
                },

                async sendMessage() {
                    if (!this.inputText.trim() || this.isTyping) return;

                    const userMsgText = this.inputText.trim();
                    this.inputText = '';
                    this.isTyping = true;

                    // Append user message immediately to local state
                    const tempUserId = 'temp_' + Date.now();
                    this.messages.push({
                        id: tempUserId,
                        role: 'user',
                        content: userMsgText
                    });
                    
                    this.scrollToBottom();

                    // Retrieve logged in user email if available
                    let email = '';
                    const savedUser = localStorage.getItem('nks_user');
                    if (savedUser) {
                        try {
                            const parsed = JSON.parse(savedUser);
                            email = parsed.email || '';
                        } catch(e) {}
                    }

                    // Build page context
                    let areaContext = '';
                    if (window.location.pathname.startsWith('/properties/')) {
                        areaContext = `Người dùng đang xem trang chi tiết: ${document.title} (${window.location.href})`;
                    } else if (window.location.pathname === '/properties') {
                        areaContext = 'Người dùng đang duyệt danh sách tìm kiếm BĐS';
                    }

                    try {
                        const csrfToken = document.querySelector('meta[name=csrf-token]')?.getAttribute('content') || '';
                        const response = await fetch('/nks-api/ai/chat', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                message: userMsgText,
                                session_id: this.sessionId,
                                email: email,
                                area_context: areaContext,
                                gender_honorific: this.getGenderHonorific()
                            })
                        });

                        const data = await response.json();
                        this.isTyping = false;

                        if (data.success && data.reply) {
                            this.messages.push({
                                id: 'ai_' + Date.now(),
                                role: 'model',
                                content: data.reply
                            });
                        } else {
                            this.messages.push({
                                id: 'ai_error_' + Date.now(),
                                role: 'model',
                                content: data.message || 'Lỗi hệ thống, vui lòng thử lại.'
                            });
                        }
                    } catch (e) {
                        this.isTyping = false;
                        this.messages.push({
                            id: 'ai_err_' + Date.now(),
                            role: 'model',
                            content: 'Không thể kết nối đến máy chủ AI. Vui lòng kiểm tra lại kết nối mạng hoặc thử lại sau.'
                        });
                    }

                    this.scrollToBottom();
                },

                scrollToBottom() {
                    this.$nextTick(() => {
                        const container = this.$refs.messageContainer;
                        if (container) {
                            container.scrollTop = container.scrollHeight;
                        }
                    });
                },

                renderMarkdown(text) {
                    if (!text) return '';
                    // Basic markdown helper for links and formatting
                    let html = text
                        .replace(/&/g, "&amp;")
                        .replace(/</g, "&lt;")
                        .replace(/>/g, "&gt;");
                    
                    // Bold: **text**
                    html = html.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                    
                    // Links: [text](url)
                    html = html.replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" class="text-indigo-600 hover:text-indigo-800 font-extrabold underline transition-all">$1</a>');
                    
                    // Bullet lists: - item or * item
                    html = html.replace(/^\s*[-*]\s+(.*)$/gm, '<li class="ml-4 list-disc my-1">$1</li>');

                    return html;
                }
            };
        };
    </script>

    @yield('scripts')
</body>
</html>
