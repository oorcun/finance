@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <table class="table table-hover text-center">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Symbol</th>
                        <th scope="col">Price</th>
                        <th scope="col">Trend</th>
                        <th scope="col">Stocks</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stocks as $stock)
                        <tr>
                            <th scope="row" class="align-middle">{{ $stock->name }}</th>
                            <td class="align-middle">{{ $stock->symbol }}</td>
                            <td id="{{ $stock->symbol }}" class="align-middle">-</td>
                            <td id="{{ $stock->symbol }}-trend" class="align-middle"><span class="badge badge-info">&#8594</span></td>
                            <td id="{{ $stock->symbol }}-count" class="align-middle">{{ $userStocks[$stock->id] }}</td>
                            <td>
                                <button id="{{ $stock->symbol }}-buy" value={{ $stock->id }} type="button" class="btn btn-primary">Buy</button>
                                <button id="{{ $stock->symbol }}-sell" value={{ $stock->id }} type="button" class="btn btn-primary">Sell</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
        <div class="col-md-2">

            <div class="jumbotron">
                <button type="button" class="btn btn-primary btn-lg btn-block" id="start" {{ $fetcherRunning ? "disabled" : "" }}>Start</button>
                <button type="button" class="btn btn-primary btn-lg btn-block" id="stop" {{ $fetcherRunning ? "" : "disabled" }}>Stop</button>
            </div>
            <div class="alert alert-dismissible alert-info">
                <strong>Balance:</strong> <span id="balance">{{ $user->balance }}</span>
            </div>
            <div class="alert alert-dismissible alert-info">
                <strong>Capital:</strong> <span id="capital">{{ $capital }}</span>
            </div>

        </div>
    </div>
</div>
@endsection

@section("script")
    <script>
        $(document).ready(function() {

            setInterval(function() {
                $.ajax({
                    url: "{{ route("stocks.get_prices") }}",
                    method: "POST",
                    success: function(prices) {
                        capitalChange = 0;
                        @foreach ($stocks as $stock)
                            stockSymbol = $("#{{ $stock->symbol }}");
                            oldPrice = stockSymbol.text();
                            stockSymbol.html(prices.{{ $stock->symbol }}.price);
                            if (prices.{{ $stock->symbol }}.status == "increased") {
                                $("#{{ $stock->symbol }}-trend").html('<span class="badge badge-success">&#8599</span>');
                            } else if (prices.{{ $stock->symbol }}.status == "decreased") {
                                $("#{{ $stock->symbol }}-trend").html('<span class="badge badge-danger">&#8600</span>');
                            }
                            capitalChange += $("#{{ $stock->symbol }}-count").text() * (prices.{{ $stock->symbol }}.price - oldPrice);
                        @endforeach
                        if ( ! isNaN(capitalChange)) {
                            capital = $("#capital");
                            capital.html(parseFloat(capital.text()) + capitalChange);
                        }
                    }
                });
            }, 1000);

            $("#start").click(function() {
                $.ajax({
                    url: "{{ route("stocks.start_fetcher") }}",
                    method: "POST",
                });
                $(this).attr("disabled", "disabled");
                $("#stop").removeAttr("disabled");
            });

            $("#stop").click(function() {
                $(this).attr("disabled", "disabled");
                $.ajax({
                    url: "{{ route("stocks.stop_fetcher") }}",
                    method: "POST",
                    success: function() {
                        $("#start").removeAttr("disabled");
                    },
                    error: function() {
                        $("#stop").removeAttr("disabled");
                    }
                });
            });

            $("[id$='buy']").click(function() {
                $(this).attr("disabled", "disabled");
                id = $(this).attr("id");
                symbol = id.substr(0, id.lastIndexOf("-"));
                price = $("#" + symbol).text()
                $.ajax({
                    url: "{{ route("stocks.buy") }}",
                    method: "POST",
                    data: {
                        "price": price,
                        "id": $(this).attr("value")
                    },
                    success: function() {
                        $("#" + id).removeAttr("disabled");
                        count = $("#" + symbol + "-count");
                        count.html(parseInt(count.text()) + 1);
                        balance = $("#balance");
                        balance.html(balance.text() - price)
                    },
                    error: function() {
                        $("#" + id).removeAttr("disabled");
                    }
                });
            });

            $("[id$='sell']").click(function() {
                $(this).attr("disabled", "disabled");
                id = $(this).attr("id");
                symbol = id.substr(0, id.lastIndexOf("-"));
                price = $("#" + symbol).text()
                $.ajax({
                    url: "{{ route("stocks.sell") }}",
                    method: "POST",
                    data: {
                        "price": price,
                        "id": $(this).attr("value")
                    },
                    success: function() {
                        $("#" + id).removeAttr("disabled");
                        count = $("#" + symbol + "-count");
                        count.html(count.text() - 1);
                        balance = $("#balance");
                        balance.html(parseFloat(balance.text()) + parseFloat(price))
                    },
                    error: function() {
                        $("#" + id).removeAttr("disabled");
                    }
                });
            });

        });
    </script>
@endsection
