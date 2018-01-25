@extends('layouts.master')

@include('partials.index.top-nav')
@section('content')
    <section class="container" id="privacy-page">
        <section class="jumbotron">
            <h1>Privacy Policy</h1>
            <h3>What personal information do we collect?</h3>
            <p>When you add Team daily praise to your Slack team we only save basic information about your team (name, id and domain)
                so we can recognise the account in our application. When a user interacts with a command or bot we collect some of their
                basic information in order to personalise each user’s experience.</p>
            <h3>How do we protect your data?</h3>
            <p>Our application is hosted on a secure server provided by a third party.
                We use a secure connection between Slack servers and ours.</p>
            <h3>Questions or concerns?</h3>
            <p>You can contact us at <a href="mailto:support@rainbowriders.dk">support@rainbowriders.dk</a></p>
        </section>
    </section>
@endsection