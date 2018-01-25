<section id="contact-section">
    <div class="container">
        <div class="row" id="contact-title">
            <h3>Contact us</h3>
        </div>
        <div class="row" id="form-container">
            {!! Form::open(array('route' => 'contact', 'method' => 'post', 'class' => 'col-md-12 col-lg-12 col-sm-12 col-cs-12')) !!}
                <div class="row">
                    <div class="col-md-5 col-g-5 col-md-offset-1 col-lg-offset-1 col-xs-12 col-sm-12">
                        {!! Form::text('name', null, array('placeholder' => 'NAME', 'id' => 'contact-name')) !!}
                        {!! Form::email('email', null, array('placeholder' => 'E-MAIL', 'id' => 'contact-email')) !!}
                        {!! Form::text('subject', null, array('placeholder' => 'SUBJECT', 'id' => 'contact-subject')) !!}
                    </div>
                    <div class="col-md-5 col-g-5 col-xs-12 col-sm-12">
                        {!! Form::textarea('message', null, array('placeholder' => 'MESSAGE', 'id' => 'contact-message')) !!}
                    </div>
                </div>
                <div class="row" id="form-send-btn">
                    {!! Form::submit('Send', array('id' => 'contact-form-btn')) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</section>