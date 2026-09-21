@php
    $tourLocale = app()->getLocale();
    $tourTranslations = [
        'en' => [
            'welcome_title' => 'Welcome to Your Admin Panel!',
            'welcome_content' => 'You are now logged in via Google. Let us show you around your new admin dashboard.',
            'sidebar_title' => 'Navigation Sidebar',
            'sidebar_content' => 'This sidebar contains all your admin modules. Click on any item to navigate between sections.',
            'dashboard_title' => 'Dashboard Overview',
            'dashboard_content' => 'This is your main dashboard showing key metrics, charts, and recent activity at a glance.',
            'users_title' => 'User Management',
            'users_content' => 'View and manage registered users. You can assign roles and permissions to users here.',
            'roles_title' => 'Role & Permissions',
            'roles_content' => 'Configure access control. Define roles and assign specific permissions to each role.',
            'profile_title' => 'Your Profile',
            'profile_content' => 'Update your personal information, change password, or modify account settings.',
            'theme_title' => 'Dark Mode Toggle',
            'theme_content' => 'Switch between light and dark theme. Your preference is saved automatically.',
            'chat_title' => 'AI Chat Assistant',
            'chat_content' => 'Need help? Use the AI chat to describe what you want to build. It will generate the code for you!',
            'finish_title' => 'You are All Set!',
            'finish_content' => 'You have completed the tour. Start exploring and building your application. The AI assistant is here to help!',
            'next' => 'Next',
            'back' => 'Back',
            'skip' => 'Skip Tour',
            'finish' => 'Finish',
            'step' => 'Step',
            'of' => 'of',
            'google_welcome' => 'Welcome! Google login detected - Let us show you around',
            'action_explore' => 'Start Exploring',
            'action_view_users' => 'View Users',
        ],
        'id' => [
            'welcome_title' => 'Selamat Datang di Panel Admin!',
            'welcome_content' => 'Anda telah login melalui Google. Mari kami tunjukkan sekilas tentang dashboard admin baru Anda.',
            'sidebar_title' => 'Sidebar Navigasi',
            'sidebar_content' => 'Sidebar ini berisi semua modul admin Anda. Klik item mana pun untuk berpindah antar bagian.',
            'dashboard_title' => 'Ringkasan Dashboard',
            'dashboard_content' => 'Ini adalah dashboard utama Anda yang menampilkan metrik kunci, grafik, dan aktivitas terkini.',
            'users_title' => 'Manajemen Pengguna',
            'users_content' => 'Lihat dan kelola pengguna terdaftar. Anda dapat menetapkan peran dan izin kepada pengguna di sini.',
            'roles_title' => 'Peran & Izin',
            'roles_content' => 'Konfigurasikan kontrol akses. Tentukan peran dan tetapkan izin spesifik untuk setiap peran.',
            'profile_title' => 'Profil Anda',
            'profile_content' => 'Perbarui informasi pribadi Anda, ubah kata sandi, atau modifikasi pengaturan akun.',
            'theme_title' => 'Tombol Mode Gelap',
            'theme_content' => 'Beralih antara tema terang dan gelap. Preferensi Anda disimpan secara otomatis.',
            'chat_title' => 'Asisten Chat AI',
            'chat_content' => 'Butuh bantuan? Gunakan chat AI untuk menjelaskan apa yang ingin Anda bangun. AI akan membuat kode untuk Anda!',
            'finish_title' => 'Anda Sudah Siap!',
            'finish_content' => 'Anda telah menyelesaikan tur. Mulai menjelajahi dan membangun aplikasi Anda. Asisten AI di sini untuk membantu!',
            'next' => 'Lanjut',
            'back' => 'Kembali',
            'skip' => 'Lewati Tur',
            'finish' => 'Selesai',
            'step' => 'Langkah',
            'of' => 'dari',
            'google_welcome' => 'Selamat datang! Login Google terdeteksi - Mari kami tunjukkan sekeliling',
            'action_explore' => 'Mulai Menjelajah',
            'action_view_users' => 'Lihat Pengguna',
        ],
    ];
    $t = $tourTranslations[$tourLocale] ?? $tourTranslations['en'];
@endphp

<script>
(function() {
    'use strict';

    const TourGuide = {
        storageKey: 'cb_admin_tour_completed',
        isActive: false,
        currentStep: 0,
        isVisible: false,
        isGoogleLogin: false,
        steps: [],
        
        get translations() {
            return @json($t);
        },
        
        get progress() {
            return {
                current: this.currentStep + 1,
                total: this.steps.length,
                percentage: Math.round(((this.currentStep + 1) / this.steps.length) * 100)
            };
        },
        
        get currentStepData() {
            return this.steps[this.currentStep] || null;
        },
        
        isCompleted() {
            return localStorage.getItem(this.storageKey) === 'true';
        },
        
        complete() {
            localStorage.setItem(this.storageKey, 'true');
            this.hide();
            this.isActive = false;
        },
        
        skip() {
            this.complete();
        },
        
        reset() {
            localStorage.removeItem(this.storageKey);
        },
        
        hide() {
            const overlay = document.getElementById('tour-guide-overlay');
            const tooltip = document.getElementById('tour-guide-tooltip');
            const googleBadge = document.getElementById('tour-guide-google-badge');
            
            if (overlay) overlay.style.opacity = '0';
            if (tooltip) tooltip.style.opacity = '0';
            if (googleBadge) googleBadge.style.opacity = '0';
            
            setTimeout(() => {
                if (overlay) overlay.remove();
                if (tooltip) tooltip.remove();
                if (googleBadge) googleBadge.remove();
            }, 300);
        },
        
        show() {
            this.isVisible = true;
            this.render();
            this.updateSpotlight();
        },
        
        render() {
            this.removeExistingElements();
            
            const overlay = document.createElement('div');
            overlay.id = 'tour-guide-overlay';
            overlay.className = 'tour-guide-overlay';
            overlay.innerHTML = '<div class="tour-guide-backdrop"></div>';
            
            const tooltip = document.createElement('div');
            tooltip.id = 'tour-guide-tooltip';
            tooltip.className = 'tour-guide-tooltip';
            
            const badge = document.createElement('div');
            badge.id = 'tour-guide-google-badge';
            badge.className = 'tour-guide-google-badge';
            
            document.body.appendChild(overlay);
            document.body.appendChild(tooltip);
            document.body.appendChild(badge);
            
            this.updateTooltip();
            this.updateGoogleBadge();
            
            requestAnimationFrame(() => {
                overlay.style.opacity = '1';
                tooltip.style.opacity = '1';
                badge.style.opacity = '1';
            });
        },
        
        removeExistingElements() {
            const existing = document.getElementById('tour-guide-overlay');
            if (existing) existing.remove();
            const existingTooltip = document.getElementById('tour-guide-tooltip');
            if (existingTooltip) existingTooltip.remove();
            const existingBadge = document.getElementById('tour-guide-google-badge');
            if (existingBadge) existingBadge.remove();
        },
        
        updateSpotlight() {
            const step = this.currentStepData;
            if (!step) return;
            
            const overlay = document.getElementById('tour-guide-overlay');
            if (!overlay) return;
            
            if (step.target === 'center') {
                overlay.innerHTML = '<div class="tour-guide-backdrop tour-guide-backdrop--center"></div>';
                return;
            }
            
            const element = document.querySelector(step.target);
            if (!element) {
                overlay.innerHTML = '<div class="tour-guide-backdrop tour-guide-backdrop--center"></div>';
                return;
            }
            
            const rect = element.getBoundingClientRect();
            const padding = 8;
            
            overlay.innerHTML = `
                <div class="tour-guide-backdrop"></div>
                <div class="tour-guide-spotlight" style="
                    position: fixed;
                    top: ${rect.top - padding}px;
                    left: ${rect.left - padding}px;
                    width: ${rect.width + padding * 2}px;
                    height: ${rect.height + padding * 2}px;
                    border-radius: 12px;
                    border: 2px solid #3471b7;
                    box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.4);
                    transition: all 0.3s ease;
                    z-index: 99998;
                "></div>
            `;
            
            if (step.placement !== 'center') {
                element.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        },
        
        updateTooltip() {
            const tooltip = document.getElementById('tour-guide-tooltip');
            const step = this.currentStepData;
            if (!tooltip || !step) return;
            
            const isLastStep = this.currentStep >= this.steps.length - 1;
            const isFirstStep = this.currentStep === 0;
            
            let positionStyle = {};
            
            if (step.target === 'center') {
                positionStyle = {
                    position: 'fixed',
                    top: '50%',
                    left: '50%',
                    transform: 'translate(-50%, -50%)'
                };
            } else {
                const element = document.querySelector(step.target);
                if (element) {
                    const rect = element.getBoundingClientRect();
                    const placement = step.placement || 'bottom';
                    const tooltipWidth = 384;
                    const gap = 12;
                    
                    let top = 0;
                    let left = 0;
                    
                    switch (placement) {
                        case 'top':
                            top = rect.top - 280 - gap + window.scrollY;
                            left = rect.left + rect.width / 2 - tooltipWidth / 2;
                            break;
                        case 'bottom':
                            top = rect.bottom + gap + window.scrollY;
                            left = rect.left + rect.width / 2 - tooltipWidth / 2;
                            break;
                        case 'left':
                            top = rect.top + rect.height / 2 - 140 + window.scrollY;
                            left = rect.left - tooltipWidth - gap;
                            break;
                        case 'right':
                            top = rect.top + rect.height / 2 - 140 + window.scrollY;
                            left = rect.right + gap;
                            break;
                        default:
                            top = rect.bottom + gap + window.scrollY;
                            left = rect.left + rect.width / 2 - tooltipWidth / 2;
                    }
                    
                    left = Math.max(16, Math.min(left, window.innerWidth - tooltipWidth - 16));
                    
                    positionStyle = {
                        position: 'absolute',
                        top: `${top}px`,
                        left: `${left}px`
                    };
                }
            }
            
            tooltip.style.cssText = Object.entries(positionStyle).map(([k, v]) => `${k}: ${v}`).join('; ') + '; z-index: 99999;';
            
            tooltip.innerHTML = `
                <div class="tour-guide-tooltip-inner">
                    <div class="tour-guide-tooltip-header">
                        <div class="tour-guide-tooltip-meta">
                            <div class="tour-guide-tooltip-icon">
                                <span class="material-symbols-outlined">orbit</span>
                            </div>
                            <span class="tour-guide-tooltip-progress">${this.translations.step} ${this.progress.current} ${this.translations.of} ${this.progress.total}</span>
                        </div>
                        <button class="tour-guide-tooltip-close" onclick="window.TourGuide.skip()">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                    <h3 class="tour-guide-tooltip-title">${step.title}</h3>
                    <p class="tour-guide-tooltip-content">${step.content}</p>
                    ${step.actionLabel ? `
                        <button class="tour-guide-tooltip-action" onclick="window.TourGuide.executeAction()">
                            <span class="material-symbols-outlined">touch_app</span>
                            ${step.actionLabel}
                        </button>
                    ` : ''}
                    <div class="tour-guide-tooltip-footer">
                        <button class="tour-guide-btn-secondary" ${isFirstStep ? 'style="visibility: hidden"' : ''} onclick="window.TourGuide.prev()">
                            <span class="material-symbols-outlined">chevron_left</span>
                            ${this.translations.back}
                        </button>
                        <div class="tour-guide-progress-dots">
                            ${this.steps.map((_, i) => `<span class="tour-guide-dot ${i < this.currentStep ? 'completed' : i === this.currentStep ? 'active' : ''}"></span>`).join('')}
                        </div>
                        <button class="tour-guide-btn-primary" onclick="window.TourGuide.next()">
                            ${isLastStep ? this.translations.finish : this.translations.next}
                            <span class="material-symbols-outlined">${isLastStep ? 'check' : 'chevron_right'}</span>
                        </button>
                    </div>
                </div>
            `;
        },
        
        updateGoogleBadge() {
            const badge = document.getElementById('tour-guide-google-badge');
            if (!badge || !this.isGoogleLogin) return;
            
            badge.innerHTML = `
                <div class="tour-guide-google-badge-inner">
                    <span class="material-symbols-outlined">auto_awesome</span>
                    <span>${this.translations.google_welcome}</span>
                    <button onclick="window.TourGuide.skip()">×</button>
                </div>
            `;
        },
        
        next() {
            const step = this.currentStepData;
            if (step && step.action && step.skipOnAction) {
                this.executeAction();
                this.complete();
                return;
            }
            
            if (step && step.action) {
                this.executeAction();
            }
            
            if (this.currentStep >= this.steps.length - 1) {
                this.complete();
                return;
            }
            
            this.currentStep++;
            this.updateSpotlight();
            this.updateTooltip();
        },
        
        prev() {
            if (this.currentStep <= 0) return;
            this.currentStep--;
            this.updateSpotlight();
            this.updateTooltip();
        },
        
        executeAction() {
            const step = this.currentStepData;
            if (step && step.action) {
                step.action();
            }
        },
        
        start(googleLogin = false) {
            if (this.isCompleted()) return;
            
            this.isGoogleLogin = googleLogin;
            this.isActive = true;
            this.currentStep = 0;
            this.isVisible = true;
            
            this.injectStyles();
            
            setTimeout(() => {
                this.show();
            }, 500);
        }
    };
    
    TourGuide.steps = [
        {
            id: 'welcome',
            target: 'center',
            title: TourGuide.translations.welcome_title,
            content: TourGuide.translations.welcome_content,
            placement: 'center'
        },
        {
            id: 'sidebar',
            target: '[data-admin-nav-item]',
            title: TourGuide.translations.sidebar_title,
            content: TourGuide.translations.sidebar_content,
            placement: 'right',
            action: () => {
                const sidebar = document.querySelector('[data-navigation-desktop="sidebar"]');
                if (sidebar) sidebar.classList.remove('hidden');
            }
        },
        {
            id: 'dashboard',
            target: 'a[href*="dashboard"]',
            title: TourGuide.translations.dashboard_title,
            content: TourGuide.translations.dashboard_content,
            placement: 'bottom'
        },
        {
            id: 'users',
            target: 'a[href*="users"]',
            title: TourGuide.translations.users_title,
            content: TourGuide.translations.users_content,
            placement: 'right'
        },
        {
            id: 'roles',
            target: 'a[href*="roles"]',
            title: TourGuide.translations.roles_title,
            content: TourGuide.translations.roles_content,
            placement: 'right'
        },
        {
            id: 'profile',
            target: 'a[href*="profile"]',
            title: TourGuide.translations.profile_title,
            content: TourGuide.translations.profile_content,
            placement: 'top'
        },
        {
            id: 'theme',
            target: '[data-theme-toggle]',
            title: TourGuide.translations.theme_title,
            content: TourGuide.translations.theme_content,
            placement: 'left'
        },
        {
            id: 'finish',
            target: 'center',
            title: TourGuide.translations.finish_title,
            content: TourGuide.translations.finish_content,
            placement: 'center',
            actionLabel: TourGuide.translations.action_explore,
            skipOnAction: true,
            action: () => {
                window.location.href = '/admin/dashboard';
            }
        }
    ];
    
    TourGuide.injectStyles = function() {
        if (document.getElementById('tour-guide-styles')) return;
        
        const styles = document.createElement('style');
        styles.id = 'tour-guide-styles';
        styles.textContent = `
            .tour-guide-overlay {
                position: fixed;
                inset: 0;
                z-index: 99997;
                transition: opacity 0.3s ease;
            }
            .tour-guide-backdrop {
                position: absolute;
                inset: 0;
                background: rgba(0, 0, 0, 0.4);
                backdrop-filter: blur(2px);
            }
            .tour-guide-backdrop--center {
                background: rgba(0, 0, 0, 0.5);
            }
            .tour-guide-tooltip {
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            .tour-guide-tooltip-inner {
                background: white;
                border-radius: 16px;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                border: 1px solid #e2e8f0;
                overflow: hidden;
                width: 384px;
                max-width: 90vw;
                font-family: 'Space Grotesk', 'Public Sans', sans-serif;
            }
            .dark .tour-guide-tooltip-inner {
                background: #1e293b;
                border-color: #334155;
            }
            .tour-guide-tooltip-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 16px 20px;
                border-bottom: 1px solid #f1f5f9;
            }
            .dark .tour-guide-tooltip-header {
                border-color: #334155;
            }
            .tour-guide-tooltip-meta {
                display: flex;
                align-items: center;
                gap: 8px;
            }
            .tour-guide-tooltip-icon {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                background: rgba(52, 113, 183, 0.1);
                display: flex;
                align-items: center;
                justify-content: center;
                color: #3471b7;
            }
            .tour-guide-tooltip-progress {
                font-size: 10px;
                font-weight: 700;
                color: #94a3b8;
                text-transform: uppercase;
                letter-spacing: 0.1em;
            }
            .tour-guide-tooltip-close {
                background: none;
                border: none;
                padding: 4px;
                border-radius: 8px;
                cursor: pointer;
                color: #94a3b8;
                transition: all 0.2s;
            }
            .tour-guide-tooltip-close:hover {
                background: #f1f5f9;
                color: #64748b;
            }
            .dark .tour-guide-tooltip-close:hover {
                background: #334155;
                color: #e2e8f0;
            }
            .tour-guide-tooltip-title {
                padding: 16px 20px 8px;
                font-size: 16px;
                font-weight: 700;
                color: #0f172a;
            }
            .dark .tour-guide-tooltip-title {
                color: #f1f5f9;
            }
            .tour-guide-tooltip-content {
                padding: 0 20px;
                font-size: 14px;
                color: #64748b;
                line-height: 1.6;
            }
            .dark .tour-guide-tooltip-content {
                color: #94a3b8;
            }
            .tour-guide-tooltip-action {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                width: calc(100% - 40px);
                margin: 16px 20px;
                padding: 10px;
                background: #3471b7;
                color: white;
                border: none;
                border-radius: 8px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.2s;
            }
            .tour-guide-tooltip-action:hover {
                background: #2851a3;
            }
            .tour-guide-tooltip-footer {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 16px 20px;
                background: #f8fafc;
                border-top: 1px solid #f1f5f9;
            }
            .dark .tour-guide-tooltip-footer {
                background: #0f172a;
                border-color: #334155;
            }
            .tour-guide-btn-primary {
                display: flex;
                align-items: center;
                gap: 4px;
                padding: 8px 16px;
                background: #3471b7;
                color: white;
                border: none;
                border-radius: 8px;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.2s;
            }
            .tour-guide-btn-primary:hover {
                background: #2851a3;
            }
            .tour-guide-btn-secondary {
                display: flex;
                align-items: center;
                gap: 4px;
                padding: 8px 16px;
                background: none;
                color: #64748b;
                border: none;
                border-radius: 8px;
                font-size: 14px;
                font-weight: 500;
                cursor: pointer;
                transition: all 0.2s;
            }
            .tour-guide-btn-secondary:hover {
                color: #3471b7;
            }
            .tour-guide-progress-dots {
                display: flex;
                gap: 6px;
            }
            .tour-guide-dot {
                height: 6px;
                border-radius: 9999px;
                background: #cbd5e1;
                transition: all 0.3s;
            }
            .dark .tour-guide-dot {
                background: #475569;
            }
            .tour-guide-dot.completed {
                width: 16px;
                background: #3471b7;
            }
            .tour-guide-dot.active {
                width: 24px;
                background: #3471b7;
            }
            .tour-guide-google-badge {
                position: fixed;
                bottom: 24px;
                left: 50%;
                transform: translateX(-50%);
                z-index: 100000;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            .tour-guide-google-badge-inner {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 8px 16px;
                background: linear-gradient(135deg, #3471b7, #f97316);
                color: white;
                border-radius: 9999px;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
                font-size: 14px;
                font-weight: 500;
            }
            .tour-guide-google-badge-inner button {
                background: none;
                border: none;
                color: white;
                opacity: 0.7;
                cursor: pointer;
                font-size: 18px;
                padding: 0;
                margin-left: 4px;
            }
            .tour-guide-google-badge-inner button:hover {
                opacity: 1;
            }
            @media (max-width: 640px) {
                .tour-guide-tooltip-inner {
                    width: calc(100vw - 32px);
                }
                .tour-guide-progress-dots {
                    display: none;
                }
            }
        `;
        document.head.appendChild(styles);
    };
    
    window.TourGuide = TourGuide;
    
    @if(session('google_login') || request()->query('google_login'))
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                window.TourGuide.start(true);
            }, 1000);
        });
    @endif
})();
</script>
