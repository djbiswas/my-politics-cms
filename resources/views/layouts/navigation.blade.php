<?php
$current_page = '';
?>
<div class="sidebar" data-color="white" data-active-color="danger">
    <div class="logo">
        <x-nav-link :href="route('dashboard')" class="simple-text logo-mini">
            <div class="logo-image-small">
                <x-application-logo  />
            </div>
        </x-nav-link>
        <x-nav-link class="simple-text logo-normal">
            {{ Auth::user()->display_name }}
        </x-nav-link>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <x-nav-li :active="request()->routeIs('dashboard')">
                <x-nav-link :href="route('dashboard')">
                    <i class="nc-icon nc-bank"></i>
                    <p>Dashboard</p>
                </x-nav-link>
            </x-nav-li>
            <x-nav-li :active="request()->routeIs('dashboard')" class="v-dropdown">
                <x-nav-link :href="route('dashboard')">
                    <i class="nc-icon nc-umbrella-13"></i>
                    <p>Politicians</p>
                    <i class="nc-icon nc-minimal-down dropdown-btn"></i>
                </x-nav-link>
                <div class="dropdown-container">
                    <a href="politicians.php" class="">All Politicians</a>
                    <a href="add-politician.php" class="">Add New</a>
                    <a href="p-categories.php" class="">Categories</a>
                </div>
            </x-nav-li>
            <x-nav-li :active="request()->routeIs('dashboard')" class="v-dropdown">
                <x-nav-link :href="route('dashboard')">
                    <i class="nc-icon nc-single-02"></i>
                    <p>Users</p>
                    <i class="nc-icon nc-minimal-down dropdown-btn"></i>
                </x-nav-link>
                <div class="dropdown-container">
                    <a href="users.php" class="">All Users</a>
                    <a href="add-user.php" class="">Add New</a>
                    <a href="user-ranks.php" class="">Ranks</a>
                </div>
            </x-nav-li>
            <x-nav-li :active="request()->routeIs('dashboard')" class="v-dropdown">
                <x-nav-link :href="route('dashboard')">
                    <i class="nc-icon nc-tag-content"></i>
                    <p>Pages</p>
                    <i class="nc-icon nc-minimal-down dropdown-btn"></i>
                </x-nav-link>
                <div class="dropdown-container">
                    <a href="pages.php" class="">All Pages</a>
                    <a href="add-page.php" class="">Add New</a>
                </div>
            </x-nav-li>
        </ul>
    </div>
</div>

