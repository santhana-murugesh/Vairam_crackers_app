    <script setup>
    import orderlist from "../Components/Partials/orderlist.vue";
    import Navbar from "../Components/Partials/Navbar.vue";

    import Footer from "../Components/Partials/Footer.vue";

    const props = defineProps({
        min_order_value: Number,
        global_discount: Number,
        email: Object,
    });

    </script>

    <template>
        <main>
            <article>

                <Navbar :global_discount="props.global_discount" :min_order_value="props.min_order_value" />

                <slot />

                <Footer :email = "props.email"/>
            </article>
        </main>
    </template>
