<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Comprar monedas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
  <div class="container mt-5">
    <h2 class="mb-4">Comprar monedas para {{ $user->name }}</h2>

    <form method="POST" action="{{ route('transactions.buy', $user->id) }}">
      @csrf
      <div class="mb-3">
        <label for="amount" class="form-label">Cantidad a comprar:</label>
        <input 
          type="number" 
          step="0.01" 
          name="amount" 
          id="amount" 
          class="form-control" 
          required 
          placeholder="Ingresa la cantidad" 
        >
      </div>
      <button type="submit" class="btn btn-primary">Comprar</button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
