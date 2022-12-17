<section class="sidebar">
  <!-- Sidebar user panel -->
  <div class="user-panel">
    <div class="pull-left image">
      <img src="{{asset("AdminLTE-2.4.15/dist/img/user2-160x160.jpg")}}" class="img-circle" alt="User Image">
    </div>
    <div class="pull-left info">
      <p>{{ Auth::user()->name }}</p>
      <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
    </div>
  </div>
  <!-- search form -->
  <form action="#" method="get" class="sidebar-form">
    <div class="input-group">
      <input type="text" name="q" class="form-control" placeholder="Search...">
      <span class="input-group-btn">
            <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
            </button>
          </span>
    </div>
  </form>
  <!-- /.search form -->
  <!-- sidebar menu: : style can be found in sidebar.less -->
  <ul class="sidebar-menu" data-widget="tree">
    <li class="header">MAIN NAVIGATION</li>
    <li class="{{($urlactive == 'subject') ? 'active' : ''}}">
      <a href="{{url('subject')}}">
        <i class="fa fa-folder"></i> <span>Mata Pelajaran</span>
      </a>
    </li>
    <li class="{{($urlactive == 'extra') ? 'active' : ''}}">
      <a href="{{url('extra')}}">
        <i class="fa fa-soccer-ball-o"></i> <span>Ekstrakurikuler</span>
      </a>
    </li>
    <li class="{{($urlactive == 'kelas') ? 'active' : ''}}">
      <a href="{{url('kelas')}}">
        <i class="fa fa-trello"></i> <span>Kelas</span>
      </a>
    </li>
    <li class="{{($urlactive == 'student') ? 'active' : ''}}">
      <a href="{{url('student')}}">
        <i class="fa fa-graduation-cap"></i> <span>Siswa</span>
      </a>
    </li>
    <li class="{{($urlactive == 'periode') ? 'active' : ''}}">
      <a href="{{url('periode')}}">
        <i class="fa fa-calendar"></i> <span>Periode</span>
      </a>
    </li>
    <li class="{{($urlactive == 'report') ? 'active' : ''}}">
      <a href="{{url('report')}}">
        <i class="fa fa-newspaper-o"></i> <span>Report</span>
      </a>
    </li>
    @if(auth()->user()->role == 'admin')
    <li class="{{($urlactive == 'user') ? 'active' : ''}}">
      <a href="{{url('user')}}">
        <i class="fa fa-users"></i> <span>User</span>
      </a>
    </li>
    @endif
  </ul>
</section>
