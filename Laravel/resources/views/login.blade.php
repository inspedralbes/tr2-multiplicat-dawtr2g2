<div class="container">
    <h2>Login</h2>

    @if($errors->has('error'))
    <div class="alert alert-danger">
        {{ $errors->first('error') }}
    </div>
    @endif
    <form method="POST" action="{{ route('loginAdmin') }}">
        @method('POST')
        @csrf

        <div>
            <label for="username">Nombre de usuario:</label>
            <input type="text" name="username" autofocus>
        </div>
        <div>
            <label for="password">Contraseña:</label>
            <input type="password" name="password">
        </div>

        <div>
            <button type="submit">Login</button>
        </div>

    </form>

</div>

<style>
    html,
    body {
        height: 100%;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .container {
        width: 300px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        color: #333;
    }

    form {
        margin-top: 20px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        color: #333;
    }

    input {
        width: 100%;
        padding: 10px;
        margin-bottom: 16px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    button {
        background-color: #4CAF50;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    button:hover {
        background-color: #45a049;
    }

    .alert {
        margin-top: 20px;
        padding: 10px;
        color: #721c24;
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        border-radius: 4px;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }
</style>