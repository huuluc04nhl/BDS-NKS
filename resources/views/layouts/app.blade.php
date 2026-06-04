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
<body class="flex flex-col h-full bg-white text-slate-800 font-sans antialiased">
    <script>
        // Synchronize backend Auth session with browser localStorage before Alpine compiles
        @if (auth()->check())
            localStorage.setItem('nks_user', JSON.stringify(@js([
                'id' => auth()->id(),
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'phone' => auth()->user()->phone,
                'role' => auth()->user()->role,
                'avatar' => auth()->user()->avatar
            ])));
        @else
            localStorage.removeItem('nks_user');
        @endif
    </script>

    <!-- Header Navigation (Exact Moso UX & Layout) -->
    <header class="sticky top-0 z-50 w-full transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-slate-100"
            x-data="{ 
                mobileMenuOpen: false, 
                isLoggedIn: false,
                user: null,
                addressToggle: false,
                init() {
                    // Synchronize backend Auth session with browser localStorage on page load/refresh
                    @if (auth()->check())
                        const dbUser = @js([
                            'id' => auth()->id(),
                            'name' => auth()->user()->name,
                            'email' => auth()->user()->email,
                            'phone' => auth()->user()->phone,
                            'role' => auth()->user()->role,
                            'avatar' => auth()->user()->avatar
                        ]);
                        localStorage.setItem('nks_user', JSON.stringify(dbUser));
                    @else
                        localStorage.removeItem('nks_user');
                    @endif

                    // Check local storage for mock user
                    const savedUser = localStorage.getItem('nks_user');
                    if (savedUser) {
                        this.isLoggedIn = true;
                        this.user = JSON.parse(savedUser);
                    }
                    
                    // Listen for login events
                    window.addEventListener('nks-login-change', () => {
                        const saved = localStorage.getItem('nks_user');
                        if (saved) {
                            this.isLoggedIn = true;
                            this.user = JSON.parse(saved);
                        } else {
                            this.isLoggedIn = false;
                            this.user = null;
                        }
                    });
                },
                async logout() {
                    try {
                        await fetch('/nks-api/logout', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                    } catch (e) {}
                    localStorage.removeItem('nks_user');
                    localStorage.removeItem('nks_appointments');
                    localStorage.removeItem('nks_favorites');
                    localStorage.removeItem('nks_owner_properties');
                    this.isLoggedIn = false;
                    this.user = null;
                    window.dispatchEvent(new CustomEvent('nks-login-change'));
                    window.location.href = '/';
                }
            }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                
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
                                        <p class="text-xs text-slate-400">Tài khoản</p>
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

    @yield('scripts')
</body>
</html>
