$(window).load(function() {

    if ($.support.pjax) {

        $.pjax.defaults.timeout = 30000;
        $(document)
            .on('pjax:send', function() {

                $('#system-message').html('Loading...');
                $('#system-message').show();

            })
            .on('pjax:complete', function() {

                $('#system-message').fadeOut();

            })
            .on('pjax:error', function(e, xhr, err) {

                $('#system-message').html('Something went wrong: ' + err);
                $('#system-message').show();

            })
            .on('pjax:popstate', function (e) {

                $.pjax({url: e.state.url, container: e.state.container, replace: true});

            });

        $(document).pjax('a.main-pjax', '#main-section');

    }

});