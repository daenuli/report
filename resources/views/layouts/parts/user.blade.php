<li class="dropdown user user-menu">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <img src="{{asset('AdminLTE-2.4.15/dist/img/user2-160x160.jpg')}}" class="user-image" alt="User Image">
    <span class="hidden-xs">{{ Auth::user()->name }}</span>
  </a>
  <ul class="dropdown-menu">
    <!-- User image -->
    <li class="user-header">
      <img src="{{asset('AdminLTE-2.4.15/dist/img/user2-160x160.jpg')}}" class="img-circle" alt="User Image">

      <p>
        {{ Auth::user()->name }}
        <small>Member since {{ Auth::user()->created_at->format('M. Y') }}</small>
      </p>
    </li>
    <li class="user-footer">
      {{-- <div class="pull-left">
        <a href="#" class="btn btn-default btn-flat">Profile</a>
      </div> --}}
      <div class="pull-right">
        <a href="{{ route('logout') }}"
                             onclick="event.preventDefault();
                                           document.getElementById('logout-form').submit();" class="btn btn-default btn-flat">Sign out</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
      </div>
    </li>
  </ul>
</li>
