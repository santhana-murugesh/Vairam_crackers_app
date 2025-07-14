<script setup>
import HomeLayout from "@/Layouts/HomeLayout.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <HomeLayout>
        <div class="container mt-5" style="min-height: 350px">
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
                                href="account-details"
                                ><i
                                    class="fa-solid fa-user"
                                    style="color: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);"
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
                                    style="color: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);"
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
                                    style="color: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);"
                                >
                                    &nbsp;</i
                                >Logout</Link
                            >
                        </li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <h4 class="mb-3">Address</h4>
                    <form class="row g-3">
                        <div class="col-md-6">
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
                        <div class="col-md-6">
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
                        <div class="col-12">
                            <label for="inputAddress" class="form-label"
                                >Address</label
                            >
                            <input
                                type="text"
                                class="form-control"
                                id="inputAddress"
                                placeholder="1234 Main St"
                            />
                        </div>
                        <div class="col-12">
                            <label for="inputAddress2" class="form-label"
                                >Address 2</label
                            >
                            <input
                                type="text"
                                class="form-control"
                                id="inputAddress2"
                                placeholder="Apartment, studio, or floor"
                            />
                        </div>
                        <div class="col-md-6">
                            <label for="inputCity" class="form-label"
                                >City</label
                            >
                            <input
                                type="text"
                                class="form-control"
                                id="inputCity"
                            />
                        </div>
                        <div class="col-md-4">
                            <label for="inputState" class="form-label"
                                >State</label
                            >
                            <select id="inputState" class="form-select">
                                <option selected>Choose...</option>
                                <option>Tamilnadu</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="inputZip" class="form-label">Zip</label>
                            <input
                                type="text"
                                class="form-control"
                                id="inputZip"
                            />
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="gridCheck"
                                />
                                <label class="form-check-label" for="gridCheck">
                                    Shipping address is the same as my billing
                                    address
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="gridCheck"
                                />
                                <label class="form-check-label" for="gridCheck">
                                    Save this information for next time
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button
                                type="submit"
                                class="btn"
                                style="background-color: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);; color: #fff"
                            >
                                Add to address
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </HomeLayout>
</template>
