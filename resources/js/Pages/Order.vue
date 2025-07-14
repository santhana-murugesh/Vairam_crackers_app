<script setup>
import HomeLayout from "@/Layouts/HomeLayout.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <HomeLayout>
        <div class="container mt-5" style="min-height: 350px">
            <div class="row">
                <div class="col col-lg-3">
                    <ul class="nav-item text-dark">
                        <li class="nav-link nav-link-p">
                            <Link
                                class="link-dark text-decoration-none"
                                style="font-size: 1.2rem"
                                href="/address"
                                ><i
                                    class="fa-solid fa-house"
                                    style="color: #63459b"
                                >
                                    &nbsp; </i
                                >Address</Link
                            >
                        </li>
                        <li class="nav-link nav-link-p">
                            <Link
                                class="link-dark text-decoration-none"
                                style="font-size: 1.2rem"
                                href="/order"
                                ><i
                                    class="fa-solid fa-cart-shopping"
                                    style="color: #63459b"
                                >
                                    &nbsp;</i
                                >Orders</Link
                            >
                        </li>
                        <li class="nav-link nav-link-p">
                            <Link
                                class="link-dark text-decoration-none"
                                style="font-size: 1.2rem"
                                href="account-details"
                                ><i
                                    class="fa-solid fa-user"
                                    style="color: #63459b"
                                >
                                    &nbsp;</i
                                >Account Details</Link
                            >
                        </li>
                        <li class="nav-link nav-link-p">
                            <Link
                                class="link-dark text-decoration-none"
                                style="font-size: 1.2rem"
                                href="user.change-password"
                                ><i
                                    class="fa-solid fa-gear"
                                    style="color: #63459b"
                                >
                                    &nbsp;</i
                                >Change Password</Link
                            >
                        </li>
                        <li class="nav-link nav-link-p">
                            <Link
                                class="link-dark text-decoration-none"
                                style="font-size: 1.2rem"
                                href="/"
                                ><i
                                    class="fa-solid fa-right-from-bracket"
                                    style="color: #63459b"
                                >
                                    &nbsp;</i
                                >Logout</Link
                            >
                        </li>
                    </ul>
                </div>
                <div class="col col-lg-8">
                    <table class="table">
                        <h1>Orders</h1>
                        <thead>
                            <tr>
                                <th scope="col">Orders</th>
                                <th scope="col">Date</th>
                                <th scope="col">Status</th>
                                <th scope="col">Total</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row" class="table-gap">#1111</th>
                                <td class="table-gap">March 1, 2023</td>
                                <td class="table-gap">Completed</td>
                                <td class="table-gap">$00,0.00 for 93 items</td>
                                <td class="table-gap">
                                    <button
                                        class="btn text-white"
                                        style="background-color: #63459b"
                                    >
                                        View
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" class="table-gap">#2222</th>
                                <td class="table-gap">March 1, 2023</td>
                                <td class="table-gap">Completed</td>
                                <td class="table-gap">$00,0.00 for 93 items</td>
                                <td class="table-gap">
                                    <button
                                        class="btn text-white"
                                        style="background-color: #63459b"
                                    >
                                        View
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" class="table-gap">#3333</th>
                                <td class="table-gap">March 18, 2023</td>
                                <td class="table-gap">Completed</td>
                                <td class="table-gap">$00,0.00 for 3 items</td>
                                <td class="table-gap">
                                    <button
                                        class="btn text-white"
                                        style="background-color: #63459b"
                                    >
                                        View
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </HomeLayout>
</template>
