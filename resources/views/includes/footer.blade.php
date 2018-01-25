    @if(Route::current()->getName() == 'home')
    <footer>
        <section class="container">
            <span class="pull-left"><a href="{{route('privacy')}}">Privacy Policy</a></span>
            <span class="pull-right"><a href="http://rainbowriders.dk/" target="_blank">Rainbow Riders 2016</a></span>
        </section>
    </footer>
    @endif
    {!! Html::script('libs/jquery.min.js') !!}
    {!! Html::script('libs/bootstrap/js/bootstrap.min.js') !!}
    {!! Html::script('js/flash-messages.js') !!}
    {!! Html::script('js/contact-form-validator.js') !!}
    {!! Html::script('js/smooth-scroll.js') !!}
    </body>
</html>
