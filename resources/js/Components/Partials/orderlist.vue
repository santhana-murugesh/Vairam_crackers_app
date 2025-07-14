<script setup>
import { Link } from "@inertiajs/vue3";

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
</script>

<template>


    <div class="top-navbar">
        <div class="top-icons">
            <i class="text-white fa-brands fa-facebook-f"></i>
            <i class="text-white fa-brands fa-instagram"></i>
        </div>

        <div class="d-flex ">

           <!-- <p class="text-light">₹{{  mobile_number_1}}</p>
           <p class="text-light">₹{{  mobile_number_2}}</p> -->
        </div>

    </div>
    <!-- top navbar -->

    <!-- <nav class="navbar navbar-1 navbar-expand-lg" id="navbar-orderlist"> -->
    <nav class="navbar-1 navbar-expand-lg" >
        <div class="container-fluid">
            <Link class="navbar-brand" style="margin-right: 0px" href="/order-now">
                <img
                    src="image/logo/logo-3.png"
                    class="logo-h"
                    alt=""
                    width="180"
                />
            </Link>
            <Link class="navbar-brand" href="/order-now">
                <img src="image/logo/logo-e3.png" class="logo-e" alt="" />
            </Link>


            <button
                class="navbar-toggler container-3"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                onclick="myFunction(this)"
            >
                <div class="bar1"></div>
                <div class="bar2"></div>
                <div class="bar3"></div>
                <div class="bar4"></div>
                <div class="bar5"></div>
            </button>

            <div
                class="collapse navbar-collapse"
                id="navbarSupportedContent"
                style="width: 0"
            >
                <ul class="mb-2 navbar-nav ms-auto mb-lg-0 nav-links-1">
                    <li class="nav-item">
                        <Link class="nav-link" href="/"
                            >Home</Link
                        >
                    </li>
                    <li class="nav-item">
                        <Link class="nav-link" href="/order-now"
                            >Order Now</Link
                        >
                    </li>

                    <li class="nav-item">
                        <Link class="nav-link" href="/about-us">About us</Link>
                    </li>

                    <li class="nav-item">
                        <Link class="nav-link >" href="/contact-us"
                            >Contact us</Link
                        >
                    </li>
                    <li class="nav-item">
                        <Link class="nav-link >" href="/combo-pack"
                            >Contact us</Link
                        >
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>
