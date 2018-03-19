<html>
    <head>
        <title>URL shortener</title>
        <link href="assets/bootstrap.min.css" rel="stylesheet" media="screen">
    </head>
    <body>
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col col-md-auto">
                    <h1>URL shortener</h1>
                    <form method="POST" action="url/" id="form" class="form-inline">
                        <div class="form-group mr-sm-3">
                            <input type="text" name="url" class="form-control" id="input-url" placeholder="URL" required="required">
                            <div id="urlInput" class="invalid-feedback"></div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                    <div id="result" class="jumbotron" style="display: none;">
                        <p class="lead">Url:</p>
                        <p id="result-url"></p>
                    </div>
                </div>
            </div>
        </div>

        <script src="assets/jquery.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                var result = $('#result');
                $('#form').on('submit', function(e) {
                    e.stopPropagation();
                    var form = $(this);
                    $.ajax(form.attr('action'), {
                        type: form.attr('method'),
                        data: form.serialize()
                    }).done(function(data) {
                        result.hide().find('#result-url').empty();
                        if (data.success) {
                            result.show().find('#result-url').text(data.url);
                        } else {
                            alert(data.errors);
                        }
                    }).fail(function(data) {
                        alert('Error: ' + data.status);
                    });
                    return false;
                });
            });
        </script>
    </body>
</html>