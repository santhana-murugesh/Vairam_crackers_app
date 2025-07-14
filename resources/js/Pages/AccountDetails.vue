<script setup>
import HomeLayout from "@/Layouts/HomeLayout.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <HomeLayout>
        <div class="container mt-5"  style="min-height: 350px">
            <div class="row">
                <div class="col col-lg-4">
                    <ul class="nav-item text-dark">
                        <li class="nav-link nav-link-p">
                            <Link
                                class="link-dark text-decoration-none"
                                style="font-size: 1.2rem"
                                href="/address"
                                ><i
                                    class="fa-solid fa-house"
                                    style="color: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);"
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
                                    style="color: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);"
                                >
                                    &nbsp;</i
                                >Orders</Link
                            >
                        </li>
                        <li class="nav-link nav-link-p">
                            <Link
                                class="link-dark text-decoration-none"
                                style="font-size: 1.2rem"
                                href="/account-details"
                                ><i
                                    class="fa-solid fa-user"
                                    style="color: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);"
                                >
                                    &nbsp; </i
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
                                    style="color: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);"
                                >
                                    &nbsp; </i
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
                                    style="color: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);"
                                >
                                    &nbsp; </i
                                >Logout</Link
                            >
                        </li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="order-md-1">
                        <h4 class="mb-3">Account details</h4>
                        <form class="needs-validation" novalidate>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="firstName">First name</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="firstName"
                                        placeholder=""
                                        value=""
                                        required
                                    />
                                    <div class="invalid-feedback">
                                        Valid first name is required.
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="lastName">Last name</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="lastName"
                                        placeholder=""
                                        value=""
                                        required
                                    />
                                    <div class="invalid-feedback">
                                        Valid last name is required.
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="username">Username</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">@</span>
                                    </div>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="username"
                                        placeholder="Username"
                                        required
                                    />
                                    <div
                                        class="invalid-feedback"
                                        style="width: 100%"
                                    >
                                        Your username is required.
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email">Email </label>
                                <input
                                    type="email"
                                    class="form-control"
                                    id="email"
                                    placeholder="you@example.com"
                                />
                                <div class="invalid-feedback">
                                    Please enter a valid email address for
                                    shipping updates.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address">Phone Number</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="address"
                                    placeholder="Enter your Number"
                                    required
                                />
                                <div class="invalid-feedback">
                                    Please enter your shipping address.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="address">Address</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="address"
                                    placeholder="1234 Main St"
                                    required
                                />
                                <div class="invalid-feedback">
                                    Please enter your shipping address.
                                </div>
                            </div>

                            <hr class="mb-4" />
                            <div class="custom-control custom-checkbox">
                                <input
                                    type="checkbox"
                                    class="custom-control-input"
                                    id="same-address"
                                />
                                <label
                                    class="custom-control-label"
                                    for="same-address"
                                    >Shipping address is the same as my billing
                                    address</label
                                >
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input
                                    type="checkbox"
                                    class="custom-control-input"
                                    id="save-info"
                                />
                                <label
                                    class="custom-control-label"
                                    for="save-info"
                                    >Save this information for next time</label
                                >
                            </div>
                            <hr class="mb-4" />
                            <div class="">
                                <button
                                    type="submit"
                                    class="btn"
                                    style="
                                        background-color: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);;
                                        color: #fff;
                                    "
                                >
                                    Confirm identity
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </HomeLayout>
</template>
