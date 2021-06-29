<div class="sidebar" data-color="white" data-active-color="danger">
    <!--
      Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red | yellow"
  -->
    <div class="logo">
        <a href="{{ env('APP_URL') }}" class="simple-text logo-normal brand-name">
            {{ env('APP_NAME') }}
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li class="{{ $menu == 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <i class="fa fa-tachometer"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="{{ $menu == 'products' ? 'active' : '' }}">
                <a href="{{ route('products.index') }}">
                    <i class="fa fa-tags"></i>
                    <p>Products</p>
                </a>
            </li>
            <li class="{{ $menu == 'projects' ? 'active' : '' }}">
                <a href="{{ route('projects.index') }}">
                    <i class="fa fa-sitemap"></i>
                    <p>Projects</p>
                </a>
            </li>
            <li class="{{ $menu == 'budget' ? 'active' : '' }}">
                <a href="#" class="nav-link dropdown-toggle simple-text logo-mini" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-money" aria-hidden="true"></i>
                    Budget
                </a>
                <div class="dropdown-menu dropdown-menu-right bg-secondary" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item text-white" href="{{ route('budget.index', 'add') }}">
                        Budget Add
                    </a>
                    <a class="dropdown-item text-white" href="{{ route('budget.index', 'list') }}">
                        Budget List
                    </a>
                </div>
            </li>
            <li class="{{ $menu == 'expense' ? 'active' : '' }}">
                <a href="#" class="nav-link dropdown-toggle simple-text logo-mini" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-credit-card-alt" aria-hidden="true"></i>
                    Expense
                </a>
                <div class="dropdown-menu dropdown-menu-right bg-secondary" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item text-white" href="{{ route('expense.index', 'add') }}">
                        Expense Add
                    </a>
                    <a class="dropdown-item text-white" href="{{ route('expense.index', 'list') }}">
                        Expense List
                    </a>
                </div>
            </li>
            <li class="{{ $menu == 'report' ? 'active' : '' }}">
                <a href="{{ route('report.index') }}">
                    <i class="fa fa-file-text-o"></i>
                    <p>Report</p>
                </a>
            </li>
            <li>
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    <i class="fa fa-power-off"></i>
                    Logout
                </a>
            </li>
        </ul>
    </div>
</div>
