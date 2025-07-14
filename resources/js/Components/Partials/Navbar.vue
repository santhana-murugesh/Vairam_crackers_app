<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";

// Get the page object from Inertia
const page = usePage();

defineProps({
    status: String,
    sliders: Object,
    products: Object,
    categories: Object,
    orders: Object,
    global_discount: Number,
    min_order_value: Number,
    mobile_number_1: Number,
    mobile_number_2: Number,
    mobile_number_3: Number,
    mobile_number_4: Number,
});

const isScrolled = ref(false);
const isMenuOpen = ref(false);
const isHeaderVisible = ref(true);
let lastScrollY = 0;

onMounted(() => {
    window.addEventListener('scroll', () => {
        const currentScrollY = window.scrollY;
        
        // Header visibility logic
        if (currentScrollY > lastScrollY && currentScrollY > 100) {
            // Scrolling down and past 100px
            isHeaderVisible.value = false;
        } else if (currentScrollY < lastScrollY) {
            // Scrolling up
            isHeaderVisible.value = true;
        }
        
        isScrolled.value = currentScrollY > 50;
        lastScrollY = currentScrollY;
    });
    
    // Debug current page - use page instead of $page
    console.log('Current page URL:', page.url);
});

const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};
</script>

<template>
    <!-- Modern Header with Footer Styling -->
    <header class="modern-header">
        <!-- Main Navigation -->
        <nav class="main-nav" :class="{ 'scrolled': isScrolled, 'hidden': !isHeaderVisible }">
            <div class="container">
                <div class="nav-content">
                    <!-- Company Name Section -->
                    <div class="logo-section">
                        <Link href="/" class="logo-link">
                            <div class="company-name-container">
                                <h1 class="company-name">{{ page.props.settings.company_name || 'RAJAMANI\'S PYROTECH' }}</h1>
                                <p class="tagline">Premium Quality Fireworks from Sivakasi</p>
                            </div>
                        </Link>
                    </div>

                    <!-- Navigation Links -->
                    <div class="nav-links" :class="{ 'active': isMenuOpen }">
                        <ul class="nav-menu">
                            <li class="nav-item">
                                <Link 
                                    href="/" 
                                    :class="[
                                        'nav-link',
                                        page.url === '/' ? 'active' : null
                                    ]"
                                >
                                    <i class="fas fa-home"></i>
                                    <span>Home</span>
                                </Link>
                            </li>
                            <li class="nav-item">
                                <Link 
                                    href="/order-now" 
                                    :class="[
                                        'nav-link',
                                        page.url.startsWith('/order-now') ? 'active' : null
                                    ]"
                                >
                                    <i class="fas fa-shopping-cart"></i>
                                    <span>Order Now</span>
                                </Link>
                            </li>
                            <li class="nav-item">
                                <Link 
                                    href="/about-us" 
                                    :class="[
                                        'nav-link',
                                        page.url.startsWith('/about-us') ? 'active' : null
                                    ]"
                                >
                                    <i class="fas fa-info-circle"></i>
                                    <span>About Us</span>
                                </Link>
                            </li>
                            <li class="nav-item">
                                <Link 
                                    href="/contact-us" 
                                    :class="[
                                        'nav-link',
                                        page.url.startsWith('/contact-us') ? 'active' : null
                                    ]"
                                >
                                    <i class="fas fa-phone"></i>
                                    <span>Contact Us</span>
                                </Link>
                            </li>
                            <li class="nav-item">
                                <!-- <Link 
                                    href="/combo-pack" 
                                    :class="[
                                        'nav-link',
                                        page.url.startsWith('/combo-pack') ? 'active' : null
                                    ]"
                                >
                                    <i class="fas fa-box"></i>
                                    <span>Combo Packs</span>
                                </Link> -->
                            </li>
                        </ul>
                    </div>

                    <!-- Mobile Menu Toggle -->
                    <button class="mobile-toggle" @click="toggleMenu" :class="{ 'active': isMenuOpen }">
                        <span class="toggle-line"></span>
                        <span class="toggle-line"></span>
                        <span class="toggle-line"></span>
                    </button>
                </div>
            </div>
        </nav>
    </header>
</template>

<style scoped>
/* Modern Header with Footer Styling */
.modern-header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    box-shadow: 0 4px 25px rgba(0, 0, 0, 0.15);
    transition: transform 0.3s ease, all 0.3s ease;
    border-bottom: 2px solid rgba(255, 215, 0, 0.2);
}

/* Header non-sticky when cart is open */
.header-non-sticky {
    position: relative !important;
    z-index: 998 !important;
    transform: translateZ(0);
}

.modern-header.hidden {
    transform: translateY(-100%);
}

.modern-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='1'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
    pointer-events: none;
}

.modern-header > * {
    position: relative;
    z-index: 2;
}



/* Main Navigation */
.main-nav {
    padding: 8px 0;
    transition: all 0.3s ease;
}

.main-nav.scrolled {
    padding: 10px 0;
    background: rgba(26, 26, 46, 0.95);
    backdrop-filter: blur(10px);
}

.nav-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

/* Logo Section */
.logo-section {
    flex: 1;
}

.logo-link {
    text-decoration: none;
    color: inherit;
}

.logo-container {
    display: flex;
    align-items: center;
}

.company-name-container {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.company-name {
    font-size: 2.2rem;
    font-weight: 800;
    margin: 0;
    margin-bottom: 0.5rem;
    background: linear-gradient(45deg, #ffd700, #ffed4e, #ffd700);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    transition: all 0.3s ease;
    text-shadow: 0 0 20px rgba(255, 215, 0, 0.3);
    letter-spacing: 1px;
}

.company-name:hover {
    transform: scale(1.02);
    filter: brightness(1.1);
    background: linear-gradient(45deg, #ffed4e, #ffd700, #ffed4e);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.tagline {
    font-size: 0.95rem;
    color: #d1d5db;
    margin: 0;
    font-style: italic;
    font-weight: 400;
    letter-spacing: 0.5px;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
}



/* Navigation Links */
.nav-links {
    flex: 2;
    display: flex;
    justify-content: center;
}

.nav-menu {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 30px;
}

.nav-item {
    position: relative;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 14px 22px;
    color: #ffffff;
    text-decoration: none;
    border-radius: 25px;
    transition: all 0.3s ease;
    font-weight: 600;
    position: relative;
    overflow: hidden;
    font-size: 0.95rem;
    letter-spacing: 0.3px;
}

.nav-link::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 215, 0, 0.1), transparent);
    transition: left 0.6s ease;
}

.nav-link:hover::before {
    left: 100%;
}

.nav-link:hover {
    background: rgba(255, 215, 0, 0.15);
    color: #ffd700;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255, 215, 0, 0.25);
    border: 1px solid rgba(255, 215, 0, 0.3);
}

.nav-link.active {
    background: rgba(255, 215, 0, 0.25);
    color: #ffd700;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255, 215, 0, 0.35);
    border: 2px solid rgba(255, 215, 0, 0.5);
    font-weight: 700;
}

.nav-link.active::before {
    left: 100%;
}

.nav-link i {
    font-size: 1rem;
}

.nav-link span {
    font-size: 0.95rem;
}

/* Mobile Toggle */
.mobile-toggle {
    display: none;
    flex-direction: column;
    gap: 4px;
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 10px;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.toggle-line {
    width: 25px;
    height: 3px;
    background: #ffffff;
    transition: all 0.3s ease;
    border-radius: 2px;
}

.mobile-toggle:hover {
    background: rgba(255, 215, 0, 0.1);
}

.mobile-toggle.active .toggle-line:nth-child(1) {
    transform: rotate(45deg) translate(5px, 5px);
}

.mobile-toggle.active .toggle-line:nth-child(2) {
    opacity: 0;
}

.mobile-toggle.active .toggle-line:nth-child(3) {
    transform: rotate(-45deg) translate(7px, -6px);
}

/* Responsive Design */
@media (max-width: 1024px) {
    .nav-menu {
        gap: 20px;
    }
    
    .nav-link {
        padding: 10px 15px;
    }
}

@media (max-width: 768px) {
    
    .main-nav {
        padding: 6px 0;
    }
    
    .logo-container {
        justify-content: center;
    }
    
    .mobile-toggle {
        display: flex;
    }
    
    .nav-links {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        backdrop-filter: blur(10px);
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
    }
    
    .nav-links.active {
        display: block;
    }
    
    .nav-menu {
        flex-direction: column;
        gap: 0;
        padding: 20px;
    }
    
    .nav-link {
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 5px;
    }
}

@media (max-width: 480px) {
    .company-name-container {
        align-items: center;
        text-align: center;
    }
    
    .company-name {
        font-size: 1.5rem;
    }
    
    .tagline {
        font-size: 0.75rem;
    }
}

/* Animation for smooth transitions */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.nav-links.active {
    animation: fadeInUp 0.3s ease-out;
}

/* Scrollbar for mobile menu */
.nav-links::-webkit-scrollbar {
    width: 4px;
}

.nav-links::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
}

.nav-links::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #ffd700, #ffed4e);
    border-radius: 2px;
}

.nav-links::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #ffed4e, #ffd700);
}
</style>
