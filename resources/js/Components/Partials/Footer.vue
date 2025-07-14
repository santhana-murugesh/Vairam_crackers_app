<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <footer class="modern-footer">
        <!-- Main Footer Content -->
        <div class="footer-main">
            <div class="container">
                <div class="row">
                    <!-- Company Information -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="footer-section">
                            <div class="footer-logo">
                                <h2 class="company-name">{{ $page.props.settings.company_name }}</h2>
                                <p class="tagline">Premium Quality Fireworks from Sivakasi</p>
                            </div>
                            
                            <div class="company-info">
                                <div class="info-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <p>{{ $page.props.settings.company_address }}</p>
                                </div>
                                
                                <div class="contact-numbers">
                                    <div v-for="number in $page.props.settings.mobile_number" :key="number" class="contact-item">
                                        <i class="fas fa-phone"></i>
                                        <a :href="'tel:' + number.Number" class="contact-link">
                                            {{ number.Name }} - {{ number.Number }}
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="info-item">
                                    <i class="fas fa-envelope"></i>
                                    <a :href="'mailto:' + $page.props.settings.email" class="contact-link">
                                        {{ $page.props.settings.email }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="col-lg-2 col-md-6 mb-4">
                        <div class="footer-section">
                            <h3 class="footer-title">Quick Links</h3>
                            <ul class="footer-links">
                                <li>
                                    <Link href="/" class="footer-link">
                                        <i class="fas fa-home"></i>
                                        Home
                                    </Link>
                                </li>
                                <li>
                                    <Link href="/order-now" class="footer-link">
                                        <i class="fas fa-shopping-cart"></i>
                                        Order Now
                                    </Link>
                                </li>
                                <li>
                                    <Link href="/about-us" class="footer-link">
                                        <i class="fas fa-info-circle"></i>
                                        About Us
                                    </Link>
                                </li>
                                <li>
                                    <Link href="/contact-us" class="footer-link">
                                        <i class="fas fa-phone"></i>
                                        Contact Us
                                    </Link>
                                </li>
                                <li>
                                    <!-- <Link href="/combo-pack" class="footer-link">
                                        <i class="fas fa-box"></i>
                                        Combo Packs
                                    </Link> -->
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Product Categories -->
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="footer-section">
                            <h3 class="footer-title">Popular Categories</h3>
                            <ul class="footer-links">
                                <li>
                                    <a href="/order-now" class="footer-link">
                                        <i class="fas fa-fire"></i>
                                        Ground Crackers
                                    </a>
                                </li>
                                <li>
                                    <a href="/order-now" class="footer-link">
                                        <i class="fas fa-rocket"></i>
                                        Sky Shots
                                    </a>
                                </li>
                                <li>
                                    <a href="/order-now" class="footer-link">
                                        <i class="fas fa-star"></i>
                                        Sparklers
                                    </a>
                                </li>
                                <li>
                                    <a href="/order-now" class="footer-link">
                                        <i class="fas fa-seedling"></i>
                                        Flower Pots
                                    </a>
                                </li>
                                <li>
                                    <a href="/order-now" class="footer-link">
                                        <i class="fas fa-bolt"></i>
                                        Electric Crackers
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Social & Newsletter -->
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="footer-section">
                            <h3 class="footer-title">Stay Connected</h3>
                            <p class="social-description">
                                Follow us for the latest updates and exclusive offers on premium fireworks.
                            </p>
                            
                            <div class="social-links">
                                <a href="#" class="social-link facebook" title="Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="https://www.instagram.com/vairam_crackers" class="social-link instagram" title="Instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#" class="social-link whatsapp" title="WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                                <a href="#" class="social-link youtube" title="YouTube">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            </div>

                            <!-- Newsletter Signup -->
                            <div class="newsletter">
                                <h4 class="newsletter-title">Newsletter</h4>
                                <p class="newsletter-text">Get updates on new arrivals and offers</p>
                                <div class="newsletter-form">
                                    <input type="email" placeholder="Enter your email" class="newsletter-input">
                                    <button class="newsletter-btn">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="copyright">
                            &copy; {{ new Date().getFullYear() }} {{ $page.props.settings.company_name }}. 
                            All rights reserved.
                        </p>
                    </div>
                    <div class="col-md-6">
                        <div class="footer-bottom-links">
                            <a href="#" class="bottom-link">Privacy Policy</a>
                            <a href="#" class="bottom-link">Terms of Service</a>
                            <a href="/contact-us" class="bottom-link">Support</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Decorative Elements -->
        <div class="footer-decoration">
            <div class="decoration-sparkle"></div>
            <div class="decoration-sparkle"></div>
            <div class="decoration-sparkle"></div>
            <div class="decoration-sparkle"></div>
            <div class="decoration-sparkle"></div>
        </div>
    </footer>
</template>

<style scoped>
/* Modern Footer Styles */
.modern-footer {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    color: #ffffff;
    position: relative;
    overflow: hidden;
    margin-top: 80px;
}

.modern-footer::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='1'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
    pointer-events: none;
}

.footer-main {
    padding: 60px 0 40px;
    position: relative;
    z-index: 2;
}

.footer-section {
    height: 100%;
}

/* Company Logo & Info */
.footer-logo {
    margin-bottom: 2rem;
}

.company-name {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    background: linear-gradient(45deg, #ffd700, #ffed4e);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.tagline {
    font-size: 0.9rem;
    color: #b0b0b0;
    margin-bottom: 0;
    font-style: italic;
}

.company-info {
    margin-top: 1.5rem;
}

.info-item, .contact-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 1rem;
    gap: 12px;
}

.info-item i, .contact-item i {
    color: #ffd700;
    font-size: 1.1rem;
    margin-top: 2px;
    width: 20px;
}

.contact-link {
    color: #e0e0e0;
    text-decoration: none;
    transition: color 0.3s ease;
}

.contact-link:hover {
    color: #ffd700;
}

/* Footer Titles */
.footer-title {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    color: #ffffff;
    position: relative;
    padding-bottom: 10px;
}

.footer-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 40px;
    height: 2px;
    background: linear-gradient(45deg, #ffd700, #ffed4e);
}

/* Footer Links */
.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 0.75rem;
}

.footer-link {
    color: #d0d0d0;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
    padding: 5px 0;
}

.footer-link:hover {
    color: #ffd700;
    transform: translateX(5px);
}

.footer-link i {
    font-size: 0.9rem;
    width: 16px;
}

/* Social Links */
.social-description {
    color: #b0b0b0;
    font-size: 0.9rem;
    margin-bottom: 1.5rem;
    line-height: 1.5;
}

.social-links {
    display: flex;
    gap: 15px;
    margin-bottom: 2rem;
}

.social-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.social-link::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1));
    transform: translateX(-100%);
    transition: transform 0.3s ease;
}

.social-link:hover::before {
    transform: translateX(100%);
}

.social-link.facebook {
    background: linear-gradient(45deg, #1877f2, #42a5f5);
    color: white;
}

.social-link.instagram {
    background: linear-gradient(45deg, #e4405f, #fd1d1d, #fcb045);
    color: white;
}

.social-link.whatsapp {
    background: linear-gradient(45deg, #25d366, #128c7e);
    color: white;
}

.social-link.youtube {
    background: linear-gradient(45deg, #ff0000, #cc0000);
    color: white;
}

.social-link:hover {
    transform: translateY(-3px) scale(1.1);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
}

/* Newsletter */
.newsletter {
    background: rgba(255, 255, 255, 0.05);
    padding: 1.5rem;
    border-radius: 15px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.newsletter-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #ffd700;
}

.newsletter-text {
    font-size: 0.85rem;
    color: #b0b0b0;
    margin-bottom: 1rem;
}

.newsletter-form {
    display: flex;
    gap: 10px;
}

.newsletter-input {
    flex: 1;
    padding: 12px 15px;
    border: none;
    border-radius: 25px;
    background: rgba(255, 255, 255, 0.1);
    color: white;
    font-size: 0.9rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.newsletter-input::placeholder {
    color: #b0b0b0;
}

.newsletter-input:focus {
    outline: none;
    border-color: #ffd700;
    box-shadow: 0 0 0 2px rgba(255, 215, 0, 0.2);
}

.newsletter-btn {
    padding: 12px 15px;
    border: none;
    border-radius: 25px;
    background: linear-gradient(45deg, #ffd700, #ffed4e);
    color: #1a1a2e;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
}

.newsletter-btn:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(255, 215, 0, 0.4);
}

/* Footer Bottom */
.footer-bottom {
    background: rgba(0, 0, 0, 0.3);
    padding: 20px 0;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.copyright {
    margin: 0;
    color: #b0b0b0;
    font-size: 0.9rem;
}

.footer-bottom-links {
    display: flex;
    justify-content: flex-end;
    gap: 20px;
}

.bottom-link {
    color: #d0d0d0;
    text-decoration: none;
    font-size: 0.9rem;
    transition: color 0.3s ease;
}

.bottom-link:hover {
    color: #ffd700;
}

/* Decorative Elements */
.footer-decoration {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 1;
}

.decoration-sparkle {
    position: absolute;
    width: 4px;
    height: 4px;
    background: #ffd700;
    border-radius: 50%;
    animation: sparkle 3s infinite;
}

.decoration-sparkle:nth-child(1) {
    top: 20%;
    left: 10%;
    animation-delay: 0s;
}

.decoration-sparkle:nth-child(2) {
    top: 60%;
    left: 20%;
    animation-delay: 1s;
}

.decoration-sparkle:nth-child(3) {
    top: 30%;
    right: 15%;
    animation-delay: 2s;
}

.decoration-sparkle:nth-child(4) {
    bottom: 40%;
    right: 25%;
    animation-delay: 0.5s;
}

.decoration-sparkle:nth-child(5) {
    bottom: 20%;
    left: 30%;
    animation-delay: 1.5s;
}

@keyframes sparkle {
    0%, 100% {
        opacity: 0;
        transform: scale(0);
    }
    50% {
        opacity: 1;
        transform: scale(1);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .footer-main {
        padding: 40px 0 30px;
    }
    
    .company-name {
        font-size: 1.5rem;
    }
    
    .footer-bottom-links {
        justify-content: center;
        margin-top: 15px;
    }
    
    .newsletter-form {
        flex-direction: column;
    }
    
    .newsletter-btn {
        align-self: stretch;
    }
    
    .social-links {
        justify-content: center;
    }
}

@media (max-width: 576px) {
    .footer-main {
        padding: 30px 0 20px;
    }
    
    .footer-section {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .footer-title::after {
        left: 50%;
        transform: translateX(-50%);
    }
    
    .social-link {
        width: 40px;
        height: 40px;
    }
}
</style>
