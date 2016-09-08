<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Reorg Research Case Study</title>

        <!-- Scripts -->
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="./css/app.css">
    </head>
    <body>
        <header>
            <div class="header-top" role="navigation">
                <div class="container-fluid">
                    <div class="header-logo">
                        <div class="navbar-brand">Reorg Research Case Study</div>
                    </div>
                    <div class="navbar-form navbar-right">
                        <a id="xlsx" href="#" class="btn btn-primary">XLSX Download</a>
                    </div>
                    <form class="navbar-form navbar-right">
                        <div class="form-group">
                            <input id="filter" class="form-control" type="text" placeholder="Search">
                        </div>
                    </form>
                    <div class="clear"></div>
                </div>
            </div>
        </header>
        <section id="main-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped footable">
                            <thead>
                                <tr>
                                    @foreach(array_keys($payments[0]->toArray()) as $column)
                                            <th>{{ $column }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($payments as $payment)
                                <tr>
                                    @foreach($payment->toArray() as $data)
                                        <td>{{ $data }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        <footer>
            <div class="container-fluid footer-container">
                <div class="row">
                    <div class="col-md-5 col-md-offset-7 footer-company">
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                <li>
                                    <a href="#" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li><a href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#">4</a></li>
                                <li><a href="#">5</a></li>
                                <li>
                                    <a href="#" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Scripts -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="./js/app.js"></script>
    </body>
</html>
