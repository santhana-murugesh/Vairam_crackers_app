<script setup>
import Navbar from "../Components/Partials/Navbar.vue";

import Footer from "../Components/Partials/Footer.vue";



</script>

<template>
    <main>
        <article>
            <Navbar />

            <slot />

            <Footer />
        </article>
    </main>
</template>
