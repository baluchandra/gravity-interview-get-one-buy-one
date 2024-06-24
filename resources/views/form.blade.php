<!DOCTYPE html>
<html lang="en">
<head>
  <title>Gravity Interview Task</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
        <div class="container mt-5">
            <h3 class="text-center">Diwali Sale Campaign</h3>
            <div class="card card-body col-md-6 m-auto">
                @if(isset($results) && $results)
                <div>
                    <h4 class="text-secondary">Results</h4>
                    <p><strong>Input Item Prices: </strong> {{ $input_prices }} </p>
                    <p><strong>Input Rule: </strong> {{ ucfirst($input_rule) }} </p>
                    <hr />
                    <p><strong>Discount Items: </strong> [{{ $results['discount'] }}] </p>
                    <p><strong>Payable Items: </strong> [{{ $results['payable'] }}] </p>
                    <a href="{{ url('/')}}" class="btn btn-success">Back</a>
                </div>
                @else
                <form method="post">
                    @csrf
                    <div class="form-group">
                        <label for="prices-label">Enter Item Prices</label>
                        <input type="text" class="form-control" id="prices" name="prices" required>
                        <small>Ex: 10, 20, 30, 40, 50, 60</small>
                    </div>
                    <div class="form-group">
                        <label for="prices-label">Rule</label>
                        <select class="form-control" name="rule" required>
                            <option value="">Select</option>
                            <option value="rule1">Rule 1</option>
                            <option value="rule2">Rule 2</option>
                            <option value="rule3">Rule 3</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
                @endif
            </div>
        </div>
    </body>
</html>
