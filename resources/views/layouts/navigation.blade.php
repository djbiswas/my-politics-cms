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
            <x-nav-li :active="request()->routeIs('politicians.index', 'categories.index', 'get.category')" class="v-dropdown">
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
                    <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.index', 'category.rank')" >
                        Categories
                    </x-nav-link>
                </div>
            </x-nav-li>
            <x-nav-li  :active="request()->routeIs('users.index', 'ranks.index', 'get.rank', 'add.user', 'get.user')" class="v-dropdown">
                <x-nav-link :href="route('users.index')">
                    <i class="nc-icon nc-single-02"></i>
                    <p>Users</p>
                    <i class="nc-icon nc-minimal-down dropdown-btn"></i>
                </x-nav-link>
                <div class="dropdown-container">
                    <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')" >
                        All Users
                    </x-nav-link>
                    <x-nav-link :href="route('add.user')" :active="request()->routeIs('add.user')" >
                        Add New
                    </x-nav-link>
                    <x-nav-link :href="route('ranks.index')" :active="request()->routeIs('ranks.index', 'get.rank')" >
                        Ranks
                    </x-nav-link>
                </div>
            </x-nav-li>
            <x-nav-li  :active="request()->routeIs('pages.index', 'get.page', 'add.page')" class="v-dropdown" >
                <x-nav-link :href="route('pages.index')" >
                    <i class="nc-icon nc-tag-content"></i>
                    <p>Pages</p>
                    <i class="nc-icon nc-minimal-down dropdown-btn"></i>
                </x-nav-link>
                <div class="dropdown-container">
                    <x-nav-link :href="route('pages.index')" :active="request()->routeIs('pages.index')" >
                        All Pages
                    </x-nav-link>
                    <x-nav-link :href="route('add.page')" :active="request()->routeIs('get.page')" >
                        Add New
                    </x-nav-link>
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

