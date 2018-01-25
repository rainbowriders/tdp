<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="row">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand nav-links" href="{{Route::current()->getName() == 'home' ? '#install-section' : route('home')}}">
                    {!! Html::image('img/logo.png', null, array('class' => 'image-responsive')) !!}
                    <span id="brand-name">Team Daily Praise</span>
                </a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    @if(Route::current()->getName() == 'home')
                        <li><a href="#install-section" class="nav-links">Home</a></li>
                        <li><a href="#about-section" class="nav-links">About</a></li>
                        <li><a href="#commands-section" class="nav-links">Commands</a></li>
                        <li><a href="#contact-section" class="nav-links">Contact us</a></li>

                    @else
                        <li>
                            <a href="{{route('home')}}">Go Home <i class="fa fa-home" aria-hidden="true"></i></a>
                        </li>
                    @endif
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</nav>
