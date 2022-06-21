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
            <x-nav-li :active="request()->routeIs('politicians.index')" class="v-dropdown">
                <x-nav-link :href="route('politicians.index')">
                    <i class="nc-icon nc-umbrella-13"></i>
                    <p>Politicians</p>
                    <i class="nc-icon nc-minimal-down dropdown-btn"></i>
                </x-nav-link>
                <div class="dropdown-container">
                    <x-nav-link :href="route('politicians.index')" :active="request()->routeIs('politicians.index')" >
                        All Politicians
                    </x-nav-link>
                    <a href="add-politician.php" class="">Add New</a>
                    <a href="p-categories.php" class="">Categories</a>
                </div>
            </x-nav-li>
            <x-nav-li  :active="request()->routeIs('users.index')" class="v-dropdown">
                <x-nav-link :href="route('users.index')">
                    <i class="nc-icon nc-single-02"></i>
                    <p>Users</p>
                    <i class="nc-icon nc-minimal-down dropdown-btn"></i>
                </x-nav-link>
                <div class="dropdown-container">
                    <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')" >
                        All Users
                    </x-nav-link>
                    <a href="add-user.php" class="">Add New</a>
                    <a href="user-ranks.php" class="">Ranks</a>
                </div>
            </x-nav-li>
            <x-nav-li  class="v-dropdown">
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
            <x-nav-li  class="v-dropdown">
                <x-nav-link :href="route('dashboard')">
                    <i class="nc-icon nc-laptop"></i>
                    <p>Lessons</p>
                    <i class="nc-icon nc-minimal-down dropdown-btn"></i>
                </x-nav-link>
                <div class="dropdown-container">
                    <a href="lesson.php" class="<?php echo ($current_page == 'lessons.php') ? 'active' : ''; ?>">All Lessons</a>
                    <a href="add-lesson.php" class="<?php echo ($current_page == 'add-lesson.php') ? 'active' : ''; ?>">Add New</a>
                </div>
            </x-nav-li>
            <x-nav-li  class="v-dropdown">
                <x-nav-link :href="route('dashboard')">
                <i class="nc-icon nc-atom"></i>
                    <p>Issues</p>
                    <i class="nc-icon nc-minimal-down dropdown-btn"></i>
                </x-nav-link>
                <div class="dropdown-container">
                    <a href="issues.php" class="<?php echo ($current_page == 'settings.php') ? 'active' : ''; ?>">All Issues</a>
                </div>
            </x-nav-li>
            <x-nav-li  class="v-dropdown">
                <x-nav-link :href="route('dashboard')">
                    <i class="nc-icon nc-settings-gear-65"></i>
                    <p>Settings</p>
                    <i class="nc-icon nc-minimal-down dropdown-btn"></i>
                </x-nav-link>
                <div class="dropdown-container">
                    <a href="settings.php" class="<?php echo ($current_page == 'settings.php') ? 'active' : ''; ?>">Global Settings</a>
                    <a href="emails.php" class="<?php echo ($current_page == 'emails.php') ? 'active' : ''; ?>">Emails</a>
                </div>
            </x-nav-li>
        </ul>
    </div>
</div>

