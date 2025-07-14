<script setup>
import HomeLayout from "@/Layouts/HomeLayout.vue";

defineProps({ products: Object });

</script>

<template>
    <HomeLayout>
        <div class="d-flex justify-content-end price-tag1 blink1">
            <a href="image/ps-crackers.pdf" download
                ><img src="image/logo/price list1.png" alt=""
            /></a>
        </div>

        <section class="mt-5">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div
                            class="px-4 col"
                            style="position: sticky; top: 14%"
                        >
                            <h1>Products List</h1>
                            <a class="dropdown-item fs-5" href="#"
                                >Sound Crackers</a
                            >
                            <a class="dropdown-item fs-5" href="#"
                                >Mega Menu Link</a
                            >
                            <a class="dropdown-item fs-5" href="#"
                                >Mega Menu Link</a
                            >
                            <a class="dropdown-item fs-5" href="#"
                                >Mega Menu Link</a
                            >
                            <a class="dropdown-item fs-5" href="#"
                                >Mega Menu Link</a
                            >
                            <a class="dropdown-item fs-5" href="#"
                                >Sound Crackers</a
                            >
                            <a class="dropdown-item fs-5" href="#"
                                >Mega Menu Link</a
                            >
                            <a class="dropdown-item fs-5" href="#"
                                >Mega Menu Link</a
                            >
                            <a class="dropdown-item fs-5" href="#"
                                >Mega Menu Link</a
                            >
                        </div>
                    </div>
                    <div class="col" :for="product in products">
                        <div class="">
                            <div class="p-4 card">
                                <div class="text-center">
                                    <a href="/kids-gun"
                                        ><img
                                            v-bind:src="
                                                'storage/' + product.image
                                            "
                                            width="200"
                                    /></a>
                                </div>
                                <div class="product-details">
                                    <span
                                        class="font-weight-bold"
                                        style="
                                            display: flex;
                                            justify-content: space-around;
                                            margin-top: 10px;
                                        "
                                        >{{ product.name }}</span
                                    >
                                    <span
                                        class="font-weight-bold"
                                        style="
                                            display: flex;
                                            justify-content: space-around;
                                            margin-top: 10px;
                                        "
                                    >
                                        {{ product.price }}
                                    </span>
                                    <div class="flex-row buttons d-flex">
                                        <div class="cart">
                                            <i class="fa fa-shopping-cart"></i>
                                        </div>
                                        <button
                                            class="btn btn-warning cart-button btn-block"
                                        >
                                            <span class="dot">1</span>Add to
                                            cart
                                        </button>
                                    </div>
                                    <!-- <div class="weight">
                                    <small>1 piece 2.5kg</small>
                                </div> -->
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </HomeLayout>
</template>
