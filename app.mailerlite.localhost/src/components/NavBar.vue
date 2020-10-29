<template>
    <div class="header">
        <b-navbar toggleable="lg" type="dark" variant="">
            <b-navbar-brand href="/" class="pl-1">
                <img width="100px" src="/logo-color.png" alt="">
            </b-navbar-brand>

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav>

                <!-- Right aligned nav items -->
                <b-navbar-nav class="ml-auto">
                    <b-nav-item href="/subscribers">Subscribers</b-nav-item>
                    <b-nav-item href="/fields">Fields</b-nav-item>
                </b-navbar-nav>
            </b-collapse>
        </b-navbar>
    </div>
</template>

<script>
    export default {
        name: "NavBar"
    }
</script>

<style scoped>
    .header {
        background: #ffffff;
    }
    .navbar-dark .navbar-nav .nav-link {
        color: #7f8c8d;
    }
    .navbar-dark .navbar-nav .nav-link:hover, .navbar-dark .navbar-nav .nav-link:focus {
         color: #000000;
     }
</style>